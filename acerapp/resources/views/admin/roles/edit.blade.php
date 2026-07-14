<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Edit Role</h1>

        <div class="bg-white shadow rounded-lg p-6">
            <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Role Name <span class="text-red-800">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                               placeholder="e.g., admin, author, reviewer">
                        <p class="mt-1 text-sm text-gray-500">Lowercase, no spaces (use underscore for multiple words)</p>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="display_name" class="block text-sm font-medium text-gray-700">Display Name <span class="text-red-800">*</span></label>
                        <input type="text" name="display_name" id="display_name" value="{{ old('display_name', $role->display_name) }}" required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                               placeholder="e.g., Administrator, Author, Reviewer">
                        <p class="mt-1 text-sm text-gray-500">Human-readable name for this role</p>
                        @error('display_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                               placeholder="Optional description of this role">{{ old('description', $role->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Permissions Section -->
                    <div class="border-t pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Permissions</label>
                                <p class="text-sm text-gray-500 mt-1">Select permissions for this role. Users assigned to this role will automatically get these permissions. Updating permissions will sync to all users with this role.</p>
                            </div>
                            <button type="button" 
                                    onclick="toggleAllPermissions()" 
                                    class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium py-2 px-4 rounded">
                                Select All
                            </button>
                        </div>

                        @php
                        $rolePermissionIds = $role->permissions->pluck('id')->toArray();
                        @endphp

                        <div class="space-y-6">
                            @foreach($permissions as $group => $groupPermissions)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-sm font-semibold text-gray-900">{{ str_replace('-', ' ', $group) }}</h3>
                                    <button type="button" 
                                            onclick="toggleGroupPermissions('{{ $group }}')" 
                                            class="text-xs text-primary-600 hover:text-primary-700 font-medium">
                                        Select All
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($groupPermissions as $permission)
                                    <label class="flex items-start space-x-2 cursor-pointer hover:bg-gray-50 p-2 rounded permission-checkbox" data-group="{{ $group }}">
                                        <input type="checkbox"
                                            name="permissions[]"
                                            value="{{ $permission->id }}"
                                            {{ in_array($permission->id, old('permissions', $rolePermissionIds)) ? 'checked' : '' }}
                                            class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500 permission-input">
                                        <div class="flex-1">
                                            <span class="text-sm font-medium text-gray-900">{{ $permission->display_name }}</span>
                                            @if($permission->description)
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $permission->description }}</p>
                                            @endif
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('permissions.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.roles.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancel
                    </a>
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
                        Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleAllPermissions() {
            const allCheckboxes = document.querySelectorAll('.permission-input');
            const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
            
            allCheckboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
            
            // Update button text
            updateSelectAllButtonText();
        }

        function toggleGroupPermissions(group) {
            const groupCheckboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"] .permission-input`);
            const allChecked = Array.from(groupCheckboxes).every(cb => cb.checked);
            
            groupCheckboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
            
            // Update main select all button text
            updateSelectAllButtonText();
        }

        function updateSelectAllButtonText() {
            const allCheckboxes = document.querySelectorAll('.permission-input');
            const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
            const selectAllButton = document.querySelector('button[onclick="toggleAllPermissions()"]');
            if (selectAllButton) {
                selectAllButton.textContent = allChecked ? 'Deselect All' : 'Select All';
            }
        }

        // Update button text on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateSelectAllButtonText();
        });
    </script>
</x-admin-layout>

