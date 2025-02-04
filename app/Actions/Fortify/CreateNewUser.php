<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        $this->downloadPlaceholderImage($user);

        return $user;
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
