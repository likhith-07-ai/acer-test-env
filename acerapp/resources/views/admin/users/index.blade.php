<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Users</h1>
            @if(auth()->user()->isSuperAdmin())
            <a href="{{ route('admin.users.create') }}"
                class="flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-2.5 rounded-lg shadow-sm transition-colors duration-200 text-sm">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="M12 5v14" />
                </svg>
                Add User
            </a>
            @endif
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div class="min-w-[150px]">
                    <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                            {{ $role->display_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-6 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'role']))
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Clear
                </a>
                @endif
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Name</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Email</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Role</th>
                            <!-- <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Permissions</th> -->
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Created</th>
                            <th class="px-3 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-primary-400 to-secondary-500 flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-2 text-sm text-gray-500">
                                {{ $user->email }}
                            </td>
                            <td class="px-3 py-2">
                                @php
                                $roleColors = [
                                'admin' => 'bg-blue-100 text-blue-800',
                                'author' => 'bg-green-100 text-green-800',
                                'reviewer' => 'bg-yellow-100 text-yellow-800',
                                'super_admin' => 'bg-purple-100 text-purple-800',
                                'public' => 'bg-gray-100 text-gray-800',
                                ];
                                $userRoleName = $user->roleModel?->name ?? 'public';
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded {{ $roleColors[$userRoleName] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $user->roleModel?->display_name ?? 'No Role' }}
                                </span>
                            </td>
                            <!-- <td class="px-3 py-2 text-sm text-gray-500">
                                @if($user->isSuperAdmin())
                                <span class="text-xs font-semibold text-purple-600">All Permissions</span>
                                @else
                                <span class="text-xs">{{ $user->permissions->count() }} permission(s)</span>
                                @endif
                            </td> -->
                            <td class="px-3 py-2 text-sm text-gray-500">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-3 py-2 text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    @if(auth()->user()->isSuperAdmin())
                                    <a href="{{ route('admin.users.show', $user) }}"
                                        class="text-gray-600 hover:text-gray-900" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    @endif
                                    @if(auth()->user()->isSuperAdmin())
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="text-gray-600 hover:text-gray-900" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    @endif
                                    @if($user->id !== auth()->id())
                                    @if(auth()->user()->isSuperAdmin())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                        class="inline"
                                        onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No users found.
                                @if(auth()->user()->isSuperAdmin())
                                <a href="{{ route('admin.users.create') }}" class="text-primary-600 hover:text-primary-900 font-medium">Create one</a>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $users->links('vendor.pagination.tailwind') }}
            </div>
            @endif
        </div>
    </div>
</x-admin-layout>