<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
            <div class="flex gap-3">
                @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('admin.users.edit', $user) }}"
                        class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-4 rounded-lg">
                        Edit
                    </a>
                @endif
                <a href="{{ route('admin.users.index') }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg">
                    Back
                </a>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-start space-x-6">
                <div class="flex-shrink-0">
                    <div
                        class="h-24 w-24 rounded-full bg-gradient-to-br from-primary-400 to-secondary-500 flex items-center justify-center text-white text-3xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $user->name }}</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <span class="text-sm text-gray-500">Email:</span>
                            <p class="text-lg font-medium text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Role:</span>
                            @php
                                $roleColors = [
                                    'admin' => 'bg-blue-100 text-blue-800',
                                    'author' => 'bg-green-100 text-green-800',
                                    'reviewer' => 'bg-yellow-100 text-yellow-800',
                                    'super_admin' => 'bg-purple-100 text-purple-800',
                                    'public' => 'bg-gray-100 text-gray-800',
                                ];
                            @endphp
                            <span
                                class="inline-block mt-1 px-3 py-1 text-sm font-semibold rounded {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Created:</span>
                            <p class="text-lg font-medium text-gray-900">{{ $user->created_at->format('d M Y, h:i A') }}
                            </p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Last Updated:</span>
                            <p class="text-lg font-medium text-gray-900">{{ $user->updated_at->format('d M Y, h:i A') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Section -->
        <div class="bg-white shadow rounded-lg p-6 mt-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Permissions</h2>
            @if($user->isSuperAdmin())
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-purple-900">Super Admin</p>
                            <p class="text-sm text-purple-700">This user has all permissions automatically.</p>
                        </div>
                    </div>
                </div>
            @elseif($user->permissions->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($user->permissions->groupBy('group') as $group => $groupPermissions)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-gray-900 mb-2">{{ str_replace('-', ' ', $group) }}</h3>
                            <ul class="space-y-1">
                                @foreach($groupPermissions as $permission)
                                    <li class="text-sm text-gray-700 flex items-center">
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $permission->display_name }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <p class="text-sm text-gray-600">No permissions assigned to this user.</p>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>