<?php

namespace App\Http\Controllers;

use App\Mail\AdminCreatedMail;
use App\Mail\RoleChangedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Traits\Loggable;

class AdminController extends Controller
{
    use Loggable;

    public function index(Request $request)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        $query = User::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('role') && !empty($request->role)) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query->orderBy('name', 'asc')->paginate(10);

        // Logando o acesso ao módulo
        $this->logAction('Admin', 'Viewing users list', 'Accessing users list.', 0);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        $requests = $user->requests()->orderBy('created_at', 'desc')->get();

        $previousUser = User::where('id', '<', $user->id)->orderBy('id', 'desc')->first();
        $nextUser = User::where('id', '>', $user->id)->orderBy('id', 'asc')->first();

        // Logando o acesso ao módulo de visualização de usuário
        $this->logAction('Admin', 'Viewing user details', 'Accessing details for user ID: ' . $user->id, $user->id);

        return view('admin.users.show', compact('user', 'requests', 'previousUser', 'nextUser'));
    }

    public function changeRole(Request $request, User $user)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Only Admins can assign roles.');

        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        // Verificar se o utilizador já tem a mesma role atribuída
        if ($user->hasRole($request->role)) {
            return back()->with('error', 'This user already has the selected role.');
        }

        // Impedir que um Admin remova a si próprio da role de Admin
        if ($user->id === auth()->id() && $request->role !== 'Admin') {
            return back()->with('error', 'You cannot remove yourself from the Admin role.');
        }

        $user->syncRoles([$request->role]);

        // Logando a mudança de role
        $this->logAction('Admin', 'Changing user role', 'Changed role for user ID: ' . $user->id . ' to ' . $request->role, $user->id);

        Mail::to($user->email)->send(new RoleChangedMail($user, $request->role));

        return back()->with('success', 'User role updated successfully.');
    }

    public function create()
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        // Logando o acesso ao módulo de criação de Admin
        $this->logAction('Admin', 'Creating new admin', 'Accessing the create admin form.', 0);

        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Only Admins can create other Admins.');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'photo' => 'nullable|image|max:2048',
        ]);

        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $admin->assignRole('Admin');

        if ($request->hasFile('photo')) {
            $admin->update([
                'profile_photo_path' => $request->file('photo')->store('profile-photos', 'public')
            ]);
        } else {
            $this->downloadPlaceholderImage($admin);
        }

         // Logando a criação de novo admin
        $this->logAction('Admin', 'Creating new admin', 'User created a new admin with email ' . $request->email, $admin->id);

        Mail::to($admin->email)->send(new AdminCreatedMail($admin, $request->password));

        return redirect()->route('admin.users.index')->with('success', 'Admin created successfully.');
    }

    protected function downloadPlaceholderImage(User $user)
    {
        $seed = rand(0, 100000);
        $imageUrl = 'https://picsum.photos/seed/'.$seed.'/300/300';
        $imageData = file_get_contents($imageUrl);

        $filename = Str::uuid().'.jpg';
        $path = 'profile-photos/'.$filename;

        Storage::disk('public')->put($path, $imageData);

        $user->forceFill([
            'profile_photo_path' => $path,
        ])->save();
    }
}
