<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Role Details</h1>
            <div class="flex gap-3">
                @if($role->name !== 'super_admin')
                    <a href="{{ route('admin.roles.edit', $role) }}"
                        class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-4 rounded">
                        Edit Role
                    </a>
                @endif
                <a href="{{ route('admin.roles.index') }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Back to Roles
                </a>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Role Name</label>
                    <p class="mt-1 text-sm text-gray-900 font-semibold">{{ $role->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Display Name</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $role->display_name }}</p>
                </div>
                @if($role->description)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $role->description }}</p>
                    </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-gray-700">Users with this Role</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $role->users()->count() }} user(s)</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Created</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $role->created_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Permissions ({{ $role->permissions->count() }})</h2>

            @if($role->permissions->count() > 0)
                <div class="space-y-6">
                    @foreach($permissions as $group => $groupPermissions)
                        @php
                            $roleGroupPermissions = $groupPermissions->filter(function ($perm) use ($role) {
                                return $role->permissions->contains('id', $perm->id);
                            });
                        @endphp
                        @if($roleGroupPermissions->count() > 0)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ str_replace('-', ' ', $group) }}</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($roleGroupPermissions as $permission)
                                        <div class="flex items-start space-x-2 p-2 rounded bg-gray-50">
                                            <input type="checkbox" checked disabled
                                                class="mt-1 rounded border-gray-300 text-primary-600">
                                            <div class="flex-1">
                                                <span class="text-sm font-medium text-gray-900">{{ $permission->display_name }}</span>
                                                @if($permission->description)
                                                    <p class="text-xs text-gray-500 mt-0.5">{{ $permission->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500">No permissions assigned to this role.</p>
            @endif
        </div>
    </div>
</x-admin-layout>