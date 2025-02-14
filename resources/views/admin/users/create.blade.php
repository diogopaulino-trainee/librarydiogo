<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Create Admin') }}</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-md rounded-lg border border-blue-200 text-lg">
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="form-control">
                <label for="name" class="label text-blue-500 font-semibold">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="w-full p-3 rounded-md bg-blue-900 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 
                    @error('name') border-red-500 @enderror"
                    placeholder="Enter Admin Name">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-control">
                <label for="email" class="label text-blue-500 font-semibold">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="w-full p-3 rounded-md bg-blue-900 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 
                    @error('email') border-red-500 @enderror"
                    placeholder="Enter Admin Email">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-control">
                <label for="password" class="label text-blue-500 font-semibold">Password</label>
                <input type="password" name="password" id="password"
                    class="w-full p-3 rounded-md bg-blue-900 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 
                    @error('password') border-red-500 @enderror"
                    placeholder="Enter Password">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-control">
                <label for="photo" class="label text-blue-500 font-semibold">Photo (Optional)</label>
                <input type="file" name="photo" id="photo"
                    class="file-input file-input-bordered w-full bg-blue-900 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 
                    @error('photo') border-red-500 @enderror"
                    accept="image/*">
                @error('photo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="w-full md:w-auto px-6 py-3 bg-blue-500 text-white font-bold rounded-md hover:bg-blue-400 transition duration-300">
                    Create Admin
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
