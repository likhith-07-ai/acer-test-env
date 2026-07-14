<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Create New User</h1>

        <div class="bg-white shadow rounded-lg p-6">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name <span
                                class="text-red-800">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email <span
                                class="text-red-800">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password <span
                                    class="text-red-800">*</span></label>
                            <input type="password" name="password" id="password" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <p class="mt-1 text-sm text-gray-500">Minimum 8 characters</p>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                                Password <span class="text-red-800">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                    </div>

                    <div>
                        <label for="role_id" class="block text-sm font-medium text-gray-700">Role <span
                                class="text-red-800">*</span></label>
                        <select name="role_id" id="role" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->display_name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Super Admin has all permissions automatically</p>
                        @error('role_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Permissions Section - Read Only (Based on Role) -->
                    <div id="permissions-section" class="border-t pt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-4">Role Permissions</label>
                        <p class="text-sm text-gray-500 mb-4">Permissions are automatically assigned based on the
                            selected role. To modify permissions, please update the role settings.</p>

                        <div id="role-permissions-display" class="space-y-6">
                            <p class="text-sm text-gray-500 italic">Select a role to see its permissions</p>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const roleSelect = document.getElementById('role');
                        const permissionsDisplay = document.getElementById('role-permissions-display');

                        if (!permissionsDisplay) return;

                        const rolePermissions = {!! $rolePermissionsJson !!};

                        function updatePermissionsDisplay() {
                            const selectedRoleId = roleSelect.value;

                            if (!selectedRoleId) {
                                permissionsDisplay.innerHTML = '<p class="text-sm text-gray-500 italic">Select a role to see its permissions</p>';
                                return;
                            }

                            const role = rolePermissions[selectedRoleId];
                            if (!role) {
                                permissionsDisplay.innerHTML = '<p class="text-sm text-gray-500 italic">Role data not found.</p>';
                                return;
                            }

                            // Check if it's super admin
                            if (role.name === 'super_admin') {
                                permissionsDisplay.innerHTML = '<p class="text-sm text-green-600 font-medium">Super Admin has all permissions automatically</p>';
                                return;
                            }

                            if (!role.permissions || Object.keys(role.permissions).length === 0) {
                                permissionsDisplay.innerHTML = '<p class="text-sm text-gray-500 italic">No permissions assigned to this role yet. Please configure the role first.</p>';
                                return;
                            }

                            let html = '';
                            for (const [group, groupPerms] of Object.entries(role.permissions)) {
                                html += `<div class="border border-gray-200 rounded-lg p-4">
                                    <h3 class="text-sm font-semibold text-gray-900 mb-3">${group.replace(/-/g, ' ')}</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">`;

                                groupPerms.forEach(permission => {
                                    html += `<div class="flex items-start space-x-2 p-2 rounded bg-gray-50">
                                        <input type="checkbox" checked disabled class="mt-1 rounded border-gray-300 text-primary-600">
                                        <div class="flex-1">
                                            <span class="text-sm font-medium text-gray-900">${permission.display_name}</span>
                                            ${permission.description ? `<p class="text-xs text-gray-500 mt-0.5">${permission.description}</p>` : ''}
                                        </div>
                                    </div>`;
                                });

                                html += `</div></div>`;
                            }

                            permissionsDisplay.innerHTML = html;
                        }

                        roleSelect.addEventListener('change', updatePermissionsDisplay);
                        updatePermissionsDisplay(); // Initial display
                    });
                </script>

                <div class="mt-6 flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.users.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>