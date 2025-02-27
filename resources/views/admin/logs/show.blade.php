<x-app-layout>
    <x-slot name="header">
        <h2>Log Details</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6 border border-blue-500 text-lg">

                @if (session('success'))
                    <div class="max-w-4xl mx-auto mt-12 mb-2">
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-md" role="alert">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-700 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                                </svg>
                                <strong class="font-bold text-green-800">Success!</strong>
                                <span class="ml-2">{{ session('success') }}</span>
                            </div>
                            <button onclick="this.parentElement.style.display='none'" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-green-700" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <title>Close</title>
                                    <path d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                @if (session('error')) 
                    <div class="max-w-4xl mx-auto mt-12 mb-2">
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-md" role="alert">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-700 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <strong class="font-bold text-red-800">Error!</strong>
                                <span class="ml-2">{{ session('error') }}</span>
                            </div>    
                            <button onclick="this.parentElement.style.display='none'" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-red-700" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <title>Close</title>
                                    <path d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif
                
                <div class="space-y-6 mb-8">
                    <div class="flex justify-between items-center">
                        <h3 class="text-3xl font-semibold text-gray-800">Log #{{ $log->id }}</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 text-gray-700">
                        <div>
                            <p><strong class="text-gray-900">User:</strong> <span class="text-gray-600">{{ $log->user_id ? $log->user->name : 'Guest' }}</span></p>
                            <p><strong class="text-gray-900">Module:</strong> <span class="text-gray-600">{{ $log->module }}</span></p>
                            <p><strong class="text-gray-900">Object ID:</strong> <span class="text-gray-600">{{ $log->object_id ?? 'N/A' }}</span></p>
                        </div>
                        <div class="text-right">
                            <p><strong class="text-gray-900">Date:</strong> <span class="text-gray-600">{{ $log->created_at->format('d M, Y') }}</span></p>
                            <p><strong class="text-gray-900">Time:</strong> <span class="text-gray-600">{{ $log->created_at->format('H:i') }}</span></p>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 p-6 rounded-md shadow-lg mb-8">
                    <h4 class="text-xl font-semibold text-gray-800 mb-4">Log Information</h4>
                    <div class="space-y-6 text-gray-700">
                        <div class="flex items-center justify-between">
                            <p class="flex-1"><strong class="font-medium text-gray-800">Description:</strong> <span class="text-gray-600">{{ $log->change_description }}</span></p>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="flex-1"><strong class="font-medium text-gray-800">Details:</strong> <span class="text-gray-600">{{ $log->details ?? 'N/A' }}</span></p>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="flex-1"><strong class="font-medium text-gray-800">IP Address:</strong> <span class="text-gray-600">{{ $log->ip_address }}</span></p>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="flex-1"><strong class="font-medium text-gray-800">Browser:</strong> <span class="text-gray-600">{{ $log->browser }}</span></p>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <a href="{{ route('admin.logs.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200">Back to Logs</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
