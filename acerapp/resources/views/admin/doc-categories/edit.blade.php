<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Edit Document Category</h1>

        <!-- Success/Error Messages -->
        <div id="subCategoryMessageContainer" class="mb-6"></div>

        <div class="bg-white shadow rounded-lg p-6">
            <form method="POST" action="{{ route('admin.doc-categories.update', $docCategory) }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Category Name <span class="text-red-800">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $docCategory->name) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="regulatory_body" class="block text-sm font-medium text-gray-700">Regulatory Body <span class="text-red-800">*</span></label>
                        <select name="regulatory_body" id="regulatory_body" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Regulatory Body</option>
                            <option value="SEBI" {{ old('regulatory_body', $docCategory->regulatory_body) == 'SEBI' ? 'selected' : '' }}>SEBI</option>
                            <option value="RBI" {{ old('regulatory_body', $docCategory->regulatory_body) == 'RBI' ? 'selected' : '' }}>RBI</option>
                            <option value="OTHER" {{ old('regulatory_body', $docCategory->regulatory_body) == 'OTHER' ? 'selected' : '' }}>OTHER</option>
                        </select>
                        @error('regulatory_body')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="short_description" class="block text-sm font-medium text-gray-700">Short Description</label>
                        <textarea name="short_description" id="short_description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('short_description', $docCategory->short_description) }}</textarea>
                        @error('short_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-gray-700">Parent Category (Optional)</label>
                        <select name="parent_id" id="parent_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">None (Main Category)</option>
                            @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" 
                                    data-regulatory-body="{{ $parent->regulatory_body }}"
                                    {{ old('parent_id', $docCategory->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.doc-categories.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancel
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Update Category
                    </button>
                </div>
            </form>
        </div>

        <!-- Sub-categories Section -->
        @if($docCategory->children->count() > 0 || !$docCategory->parent_id)
        <div class="bg-white shadow rounded-lg p-6 mt-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900">Sub-categories</h2>
                <button type="button" onclick="openSubCategoryModal()" class="text-sm bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded">
                    + Add Sub-category
                </button>
            </div>

            @if($docCategory->children->count() > 0)
            <div class="space-y-3">
                @foreach($docCategory->children as $child)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200" id="sub-category-{{ $child->id }}">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900">{{ $child->name }}</div>
                        @if($child->short_description)
                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($child->short_description, 100) }}</div>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="button" onclick="editSubCategory({{ $child->id }}, '{{ addslashes($child->name) }}', '{{ addslashes($child->short_description ?? '') }}')"
                            class="text-indigo-600 hover:text-indigo-900 p-2 rounded hover:bg-indigo-50" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button type="button" onclick="deleteSubCategory({{ $child->id }}, '{{ addslashes($child->name) }}')"
                            class="text-red-600 hover:text-red-900 p-2 rounded hover:bg-red-50" title="Delete">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <p>No sub-categories yet. Click "Add Sub-category" to create one.</p>
            </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Sub-category Modal -->
    <div id="subCategoryModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4" id="subCategoryModalTitle">Add Sub-category</h3>
                <form id="subCategoryForm">
                    @csrf
                    <input type="hidden" id="sub_category_id" name="sub_category_id" value="">
                    <input type="hidden" name="parent_id" value="{{ $docCategory->id }}">
                    <div class="mb-4">
                        <label for="sub_cat_name" class="block text-sm font-medium text-gray-700">Sub-category Name <span class="text-red-800">*</span></label>
                        <input type="text" name="name" id="sub_cat_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label for="sub_cat_description" class="block text-sm font-medium text-gray-700">Short Description</label>
                        <textarea name="short_description" id="sub_cat_description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" onclick="closeSubCategoryModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            <span id="subCategorySubmitText">Add</span> Sub-category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Sub-category Confirmation Modal -->
    <div id="deleteSubCategoryModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 ml-3">Delete Sub-category</h3>
                </div>
                <div class="mt-2">
                    <p class="text-sm text-gray-500">Are you sure you want to delete this sub-category? This action cannot be undone.</p>
                    <p class="text-sm font-medium text-gray-900 mt-2" id="deleteSubCategoryName"></p>
                    <!-- Error Message Container -->
                    <div id="deleteSubCategoryErrorContainer" class="mt-4 hidden"></div>
                </div>
                <input type="hidden" id="deleteSubCategoryId" value="">
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeDeleteSubCategoryModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancel
                    </button>
                    <button type="button" id="confirmDeleteSubCategoryBtn" onclick="confirmDeleteSubCategory()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to show success/error messages
        function showSubCategoryMessage(message, type = 'success') {
            const container = document.getElementById('subCategoryMessageContainer');
            const bgColor = type === 'success' ? 'bg-primary-50 border-primary-500 text-primary-800' : 'bg-red-50 border-red-500 text-red-800';
            const iconColor = type === 'success' ? 'text-primary-500' : 'text-red-800';
            const iconPath = type === 'success' ?
                'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' :
                'M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z';

            container.innerHTML = `
                <div class="mb-6 ${bgColor} border-l-4 px-6 py-4 rounded-r-lg shadow-sm" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 ${iconColor}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="${iconPath}" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">${message}</span>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;

            // Scroll to top to show message
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

            // Auto-hide after 5 seconds
            setTimeout(() => {
                const messageDiv = container.querySelector('div');
                if (messageDiv) {
                    messageDiv.style.transition = 'opacity 0.5s';
                    messageDiv.style.opacity = '0';
                    setTimeout(() => messageDiv.remove(), 500);
                }
            }, 5000);
        }

        function openSubCategoryModal() {
            document.getElementById('subCategoryModalTitle').textContent = 'Add Sub-category';
            document.getElementById('subCategorySubmitText').textContent = 'Add';
            document.getElementById('sub_category_id').value = '';
            document.getElementById('sub_cat_name').value = '';
            document.getElementById('sub_cat_description').value = '';
            document.getElementById('subCategoryModal').classList.remove('hidden');
        }

        function closeSubCategoryModal() {
            document.getElementById('subCategoryModal').classList.add('hidden');
            document.getElementById('subCategoryForm').reset();
        }

        function editSubCategory(id, name, description) {
            document.getElementById('subCategoryModalTitle').textContent = 'Edit Sub-category';
            document.getElementById('subCategorySubmitText').textContent = 'Update';
            document.getElementById('sub_category_id').value = id;
            document.getElementById('sub_cat_name').value = name;
            document.getElementById('sub_cat_description').value = description;
            document.getElementById('subCategoryModal').classList.remove('hidden');
        }

        function deleteSubCategory(id, name) {
            openDeleteSubCategoryModal(id, name);
        }

        function openDeleteSubCategoryModal(id, name) {
            document.getElementById('deleteSubCategoryId').value = id;
            document.getElementById('deleteSubCategoryName').textContent = name;
            document.getElementById('deleteSubCategoryErrorContainer').classList.add('hidden');
            document.getElementById('deleteSubCategoryErrorContainer').innerHTML = '';
            document.getElementById('deleteSubCategoryModal').classList.remove('hidden');
        }

        function closeDeleteSubCategoryModal() {
            document.getElementById('deleteSubCategoryModal').classList.add('hidden');
            document.getElementById('deleteSubCategoryId').value = '';
            document.getElementById('deleteSubCategoryErrorContainer').classList.add('hidden');
            document.getElementById('deleteSubCategoryErrorContainer').innerHTML = '';
        }

        function showDeleteSubCategoryError(message) {
            const errorContainer = document.getElementById('deleteSubCategoryErrorContainer');
            errorContainer.innerHTML = `
                <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">${message}</p>
                        </div>
                    </div>
                </div>
            `;
            errorContainer.classList.remove('hidden');
        }

        function confirmDeleteSubCategory() {
            const id = document.getElementById('deleteSubCategoryId').value;
            if (!id) return;
            
            const deleteBtn = document.getElementById('confirmDeleteSubCategoryBtn');
            const originalText = deleteBtn.textContent;
            deleteBtn.disabled = true;
            deleteBtn.textContent = 'Deleting...';
            
            fetch('{{ route("admin.doc-categories.destroy", ":id") }}'.replace(':id', id), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    closeDeleteSubCategoryModal();
                    if (data.success) {
                        const subCategoryElement = document.getElementById('sub-category-' + id);
                        if (subCategoryElement) {
                            subCategoryElement.style.transition = 'opacity 0.3s';
                            subCategoryElement.style.opacity = '0';
                            setTimeout(() => {
                                subCategoryElement.remove();
                                // Check if no sub-categories left
                                const subCategoriesContainer = document.querySelector('.space-y-3');
                                if (subCategoriesContainer && subCategoriesContainer.children.length === 0) {
                                    window.location.reload();
                                }
                            }, 300);
                        }
                        showSubCategoryMessage(data.message || 'Sub-category deleted successfully.', 'success');
                    } else {
                        showSubCategoryMessage(data.message || 'Failed to delete sub-category.', 'error');
                    }
                    deleteBtn.disabled = false;
                    deleteBtn.textContent = originalText;
                })
                .catch(error => {
                    console.error('Error:', error);
                    showDeleteSubCategoryError('An error occurred while deleting the sub-category.');
                    deleteBtn.disabled = false;
                    deleteBtn.textContent = originalText;
                });
        }

        document.getElementById('subCategoryForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const subCategoryId = document.getElementById('sub_category_id').value;
            const url = subCategoryId ?
                '{{ route("admin.doc-categories.update", ":id") }}'.replace(':id', subCategoryId) :
                '{{ route("admin.doc-categories.store") }}';
            const method = subCategoryId ? 'PUT' : 'POST';

            if (method === 'PUT') {
                formData.append('_method', 'PUT');
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success || response.ok) {
                    closeSubCategoryModal();
                    showSubCategoryMessage(data.message || (subCategoryId ? 'Sub-category updated successfully.' : 'Sub-category created successfully.'), 'success');
                    // Reload page after 1 second to show updated sub-categories
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    let errorMessage = data.message || 'Failed to save sub-category.';
                    if (data.errors) {
                        const errorList = Object.values(data.errors).flat().join(', ');
                        errorMessage = errorList || errorMessage;
                    }
                    showSubCategoryMessage(errorMessage, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showSubCategoryMessage('An error occurred while saving the sub-category.', 'error');
            }
        });

        // Close delete modal when clicking outside
        document.getElementById('deleteSubCategoryModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteSubCategoryModal();
            }
        });

        // Filter parent categories based on regulatory body selection
        function filterParentCategories() {
            const regulatoryBody = document.getElementById('regulatory_body').value;
            const parentSelect = document.getElementById('parent_id');
            const options = parentSelect.querySelectorAll('option');
            
            // Keep the first option (None)
            options.forEach((option, index) => {
                if (index === 0) {
                    return; // Keep "None (Main Category)" option
                }
                
                const optionRegulatoryBody = option.getAttribute('data-regulatory-body');
                
                if (regulatoryBody && optionRegulatoryBody !== regulatoryBody) {
                    option.style.display = 'none';
                } else {
                    option.style.display = '';
                }
            });
            
            // If no regulatory body selected or selected parent doesn't match, reset parent selection
            if (regulatoryBody) {
                const selectedOption = parentSelect.options[parentSelect.selectedIndex];
                if (selectedOption && selectedOption.value && selectedOption.getAttribute('data-regulatory-body') !== regulatoryBody) {
                    parentSelect.value = '';
                }
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const regulatoryBodySelect = document.getElementById('regulatory_body');
            if (regulatoryBodySelect) {
                // Filter on initial load
                filterParentCategories();
                
                // Filter when regulatory body changes
                regulatoryBodySelect.addEventListener('change', filterParentCategories);
            }
        });
    </script>
</x-admin-layout>