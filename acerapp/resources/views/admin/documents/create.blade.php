<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Upload New Document</h1>

        <div class="bg-white shadow rounded-lg p-6">
            <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="regulator" class="block text-sm font-medium text-gray-700">Regulatory Body <span
                                class="text-red-800">*</span></label>
                        <select name="regulator" id="regulator" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Regulator</option>
                            <option value="SEBI" {{ old('regulator') == 'SEBI' ? 'selected' : '' }}>SEBI</option>
                            <option value="RBI" {{ old('regulator') == 'RBI' ? 'selected' : '' }}>RBI</option>
                            <option value="OTHER" {{ old('regulator') == 'OTHER' ? 'selected' : '' }}>OTHER</option>
                        </select>
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Document Title <span
                                class="text-red-800">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Category <span
                                    class="text-red-800">*</span></label>
                            <button type="button" onclick="openCategoryModal()"
                                class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                + Add Category
                            </button>
                        </div>
                        <select name="category_id" id="category_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" data-regulatory-body="{{ $category->regulatory_body }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="sub_category_id" class="block text-sm font-medium text-gray-700">Sub
                                Category</label>
                            <button type="button" onclick="openSubCategoryModal()"
                                class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                + Add Sub Category
                            </button>
                        </div>
                        <select name="sub_category_id" id="sub_category_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Sub Category</option>
                            @foreach($categories as $category)
                                @foreach($category->children as $subCategory)
                                    <option value="{{ $subCategory->id }}" data-parent="{{ $category->id }}"
                                        data-regulatory-body="{{ $category->regulatory_body }}" {{ old('sub_category_id') == $subCategory->id ? 'selected' : '' }}>{{ $subCategory->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="access_type" class="block text-sm font-medium text-gray-700">Status / Access Type
                            <span class="text-red-800">*</span></label>
                        <select name="access_type" id="access_type" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="public" {{ old('access_type') == 'public' ? 'selected' : '' }}>Public</option>
                            <option value="restricted" {{ old('access_type') == 'restricted' ? 'selected' : '' }}>
                                Restricted</option>
                        </select>
                    </div>

                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-700">Upload Document (PDF/DOC/DOCX)
                            <span class="text-red-800">*</span></label>
                        <input type="file" name="file" id="file" accept=".pdf,.doc,.docx" required
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.documents.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Upload Document
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Management Modal -->
    <div id="categoryModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add Document Category</h3>
                <!-- Error Message Container -->
                <div id="categoryErrorContainer" class="mb-4 hidden"></div>
                <form id="categoryForm">
                    @csrf
                    <div class="mb-4">
                        <label for="cat_name" class="block text-sm font-medium text-gray-700">Category Name <span
                                class="text-red-800">*</span></label>
                        <input type="text" name="name" id="cat_name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <div id="cat_name_error" class="mt-1 text-sm text-red-600 hidden"></div>
                    </div>
                    <div class="mb-4">
                        <label for="cat_regulatory_body" class="block text-sm font-medium text-gray-700">Regulatory Body
                            <span class="text-red-800">*</span></label>
                        <select name="regulatory_body" id="cat_regulatory_body" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-100">
                            <option value="">Select Regulatory Body</option>
                            <option value="SEBI">SEBI</option>
                            <option value="RBI">RBI</option>
                            <option value="OTHER">OTHER</option>
                        </select>
                        <div id="cat_regulatory_body_error" class="mt-1 text-sm text-red-600 hidden"></div>
                    </div>
                    <div class="mb-4">
                        <label for="cat_description" class="block text-sm font-medium text-gray-700">Short
                            Description</label>
                        <textarea name="short_description" id="cat_description" rows="2"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        <div id="cat_description_error" class="mt-1 text-sm text-red-600 hidden"></div>
                    </div>
                    {{-- <div class="mb-4">
                        <label for="cat_parent_id" class="block text-sm font-medium text-gray-700">Parent Category
                            (Optional)</label>
                        <select name="parent_id" id="cat_parent_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">None (Main Category)</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" onclick="closeCategoryModal()"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Add Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sub Category Management Modal -->
    <div id="subCategoryModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add Document Sub Category</h3>
                <!-- Error Message Container -->
                <div id="subCategoryErrorContainer" class="mb-4 hidden"></div>
                <form id="subCategoryForm">
                    @csrf
                    <div class="mb-4">
                        <label for="sub_cat_name" class="block text-sm font-medium text-gray-700">Sub Category Name
                            <span class="text-red-800">*</span></label>
                        <input type="text" name="name" id="sub_cat_name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <div id="sub_cat_name_error" class="mt-1 text-sm text-red-600 hidden"></div>
                    </div>
                    <div class="mb-4">
                        <label for="sub_cat_description" class="block text-sm font-medium text-gray-700">Short
                            Description</label>
                        <textarea name="short_description" id="sub_cat_description" rows="2"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        <div id="sub_cat_description_error" class="mt-1 text-sm text-red-600 hidden"></div>
                    </div>
                    <div class="mb-4">
                        <label for="sub_cat_parent_id" class="block text-sm font-medium text-gray-700">Parent Category
                            <span class="text-red-800">*</span></label>
                        <select name="parent_id" id="sub_cat_parent_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-100">
                            <option value="">Select Parent Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" data-regulatory-body="{{ $category->regulatory_body }}">
                                    {{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div id="sub_cat_parent_id_error" class="mt-1 text-sm text-red-600 hidden"></div>
                    </div>
                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" onclick="closeSubCategoryModal()"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Add Sub Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Filter categories based on regulatory body
        function filterCategories() {
            const regulatoryBody = document.getElementById('regulator').value;
            const categorySelect = document.getElementById('category_id');
            const categoryOptions = categorySelect.querySelectorAll('option[data-regulatory-body]');
            const selectedCategoryId = categorySelect.value;

            // Show/hide categories based on selected regulatory body
            categoryOptions.forEach(option => {
                if (regulatoryBody && option.dataset.regulatoryBody === regulatoryBody) {
                    option.style.display = '';
                } else {
                    // Hide categories if no regulatory body selected or doesn't match
                    option.style.display = 'none';
                }
            });

            // If no regulatory body selected or selected category doesn't match, clear it and sub-category
            if (!regulatoryBody || (regulatoryBody && selectedCategoryId)) {
                const selectedOption = categorySelect.querySelector(`option[value="${selectedCategoryId}"]`);
                if (!regulatoryBody || (selectedOption && selectedOption.dataset.regulatoryBody !== regulatoryBody)) {
                    categorySelect.value = '';
                    document.getElementById('sub_category_id').value = '';
                }
            }

            // Trigger sub-category filter
            filterSubCategories();
        }

        // Filter sub-categories based on selected category
        function filterSubCategories() {
            const categoryId = document.getElementById('category_id').value;
            const subCategorySelect = document.getElementById('sub_category_id');
            const subOptions = subCategorySelect.querySelectorAll('option[data-parent]');
            const selectedSubCategoryId = subCategorySelect.value;

            // Show/hide sub-categories based on selected category
            subOptions.forEach(option => {
                if (categoryId && option.dataset.parent === categoryId) {
                    option.style.display = '';
                } else {
                    // Hide sub-categories if no category selected or doesn't belong to selected category
                    option.style.display = 'none';
                }
            });

            // If no category selected or selected sub-category doesn't belong to selected category, clear it
            if (!categoryId || (categoryId && selectedSubCategoryId)) {
                const selectedOption = subCategorySelect.querySelector(`option[value="${selectedSubCategoryId}"]`);
                if (!categoryId || (selectedOption && selectedOption.dataset.parent !== categoryId)) {
                    subCategorySelect.value = '';
                }
            }
        }

        // Initialize filters on page load
        document.addEventListener('DOMContentLoaded', function () {
            // Hide all categories and sub-categories by default
            const categorySelect = document.getElementById('category_id');
            const categoryOptions = categorySelect.querySelectorAll('option[data-regulatory-body]');
            categoryOptions.forEach(option => {
                option.style.display = 'none';
            });

            const subCategorySelect = document.getElementById('sub_category_id');
            const subOptions = subCategorySelect.querySelectorAll('option[data-parent]');
            subOptions.forEach(option => {
                option.style.display = 'none';
            });

            // Then apply filters based on selected regulatory body
            filterCategories();
        });

        // Filter categories when regulatory body changes
        document.getElementById('regulator').addEventListener('change', filterCategories);

        // Filter sub-categories when category changes
        document.getElementById('category_id').addEventListener('change', filterSubCategories);

        function openCategoryModal() {
            const regulatoryBody = document.getElementById('regulator').value;
            const modalRegulatoryBody = document.getElementById('cat_regulatory_body');

            // Clear previous errors
            clearCategoryErrors();

            // Pre-fill regulatory body from main form and make it read-only if selected
            if (regulatoryBody) {
                modalRegulatoryBody.value = regulatoryBody;
                modalRegulatoryBody.disabled = true;
            } else {
                modalRegulatoryBody.disabled = false;
            }

            document.getElementById('categoryModal').classList.remove('hidden');
        }

        function clearCategoryErrors() {
            document.getElementById('categoryErrorContainer').classList.add('hidden');
            document.getElementById('categoryErrorContainer').innerHTML = '';
            document.getElementById('cat_name_error').classList.add('hidden');
            document.getElementById('cat_name_error').textContent = '';
            document.getElementById('cat_regulatory_body_error').classList.add('hidden');
            document.getElementById('cat_regulatory_body_error').textContent = '';
            document.getElementById('cat_description_error').classList.add('hidden');
            document.getElementById('cat_description_error').textContent = '';
        }

        function showCategoryErrors(errors) {
            const errorContainer = document.getElementById('categoryErrorContainer');
            let errorHtml = '<div class="bg-red-50 border-l-4 border-red-500 p-4 rounded"><div class="flex"><div class="flex-shrink-0"><svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg></div><div class="ml-3"><h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3><div class="mt-2 text-sm text-red-700"><ul class="list-disc list-inside">';

            const errorMessages = new Set();

            // Show general errors
            if (errors.message) {
                errorMessages.add(errors.message);
                errorHtml += '<li>' + errors.message + '</li>';
            }

            // Show field-specific errors (only if not already shown as general message)
            Object.keys(errors).forEach(field => {
                if (field !== 'message' && Array.isArray(errors[field])) {
                    errors[field].forEach(error => {
                        if (!errorMessages.has(error)) {
                            errorMessages.add(error);
                            errorHtml += '<li>' + error + '</li>';
                        }
                    });

                    // Show error under specific field
                    const fieldErrorElement = document.getElementById('cat_' + field.replace('.', '_') + '_error');
                    if (fieldErrorElement) {
                        fieldErrorElement.textContent = errors[field][0];
                        fieldErrorElement.classList.remove('hidden');
                    }
                }
            });

            errorHtml += '</ul></div></div></div></div>';
            errorContainer.innerHTML = errorHtml;
            errorContainer.classList.remove('hidden');
        }

        function closeCategoryModal() {
            document.getElementById('categoryModal').classList.add('hidden');
            document.getElementById('categoryForm').reset();
            clearCategoryErrors();
            // Re-enable regulatory body dropdown when closing modal
            document.getElementById('cat_regulatory_body').disabled = false;
        }

        function openSubCategoryModal() {
            const selectedCategoryId = document.getElementById('category_id').value;
            const regulatoryBody = document.getElementById('regulator').value;
            const parentSelect = document.getElementById('sub_cat_parent_id');
            const parentOptions = parentSelect.querySelectorAll('option[data-regulatory-body]');

            // Clear previous errors
            clearSubCategoryErrors();

            // Filter parent categories by regulatory body
            parentOptions.forEach(option => {
                if (regulatoryBody && option.dataset.regulatoryBody === regulatoryBody) {
                    option.style.display = '';
                } else if (!regulatoryBody) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });

            // Pre-fill parent category if a category is selected
            if (selectedCategoryId) {
                parentSelect.value = selectedCategoryId;
                parentSelect.disabled = true;
                parentSelect.classList.add('bg-gray-100');
            } else {
                parentSelect.disabled = false;
                parentSelect.classList.remove('bg-gray-100');
            }

            document.getElementById('subCategoryModal').classList.remove('hidden');
        }

        function clearSubCategoryErrors() {
            document.getElementById('subCategoryErrorContainer').classList.add('hidden');
            document.getElementById('subCategoryErrorContainer').innerHTML = '';
            document.getElementById('sub_cat_name_error').classList.add('hidden');
            document.getElementById('sub_cat_name_error').textContent = '';
            document.getElementById('sub_cat_description_error').classList.add('hidden');
            document.getElementById('sub_cat_description_error').textContent = '';
            document.getElementById('sub_cat_parent_id_error').classList.add('hidden');
            document.getElementById('sub_cat_parent_id_error').textContent = '';
        }

        function showSubCategoryErrors(errors) {
            const errorContainer = document.getElementById('subCategoryErrorContainer');
            let errorHtml = '<div class="bg-red-50 border-l-4 border-red-500 p-4 rounded"><div class="flex"><div class="flex-shrink-0"><svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg></div><div class="ml-3"><h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3><div class="mt-2 text-sm text-red-700"><ul class="list-disc list-inside">';

            const errorMessages = new Set();
            const fieldErrors = {};

            // Collect all unique error messages
            if (errors.message) {
                errorMessages.add(errors.message);
                errorHtml += '<li>' + errors.message + '</li>';
            }

            // Collect field-specific errors
            Object.keys(errors).forEach(field => {
                if (field !== 'message' && Array.isArray(errors[field])) {
                    errors[field].forEach(error => {
                        if (!errorMessages.has(error)) {
                            errorMessages.add(error);
                            errorHtml += '<li>' + error + '</li>';
                        }
                        // Store field-specific error for display below field
                        if (!fieldErrors[field]) {
                            fieldErrors[field] = error;
                        }
                    });
                }
            });

            errorHtml += '</ul></div></div></div></div>';
            errorContainer.innerHTML = errorHtml;
            errorContainer.classList.remove('hidden');

            // Show errors below specific fields (only if not already shown in general list)
            Object.keys(fieldErrors).forEach(field => {
                const fieldErrorElement = document.getElementById('sub_cat_' + field.replace('.', '_') + '_error');
                if (fieldErrorElement) {
                    // Only show if this specific error message wasn't already shown in general list
                    if (!errorMessages.has(fieldErrors[field])) {
                        fieldErrorElement.textContent = fieldErrors[field];
                        fieldErrorElement.classList.remove('hidden');
                    }
                }
            });
        }

        function closeSubCategoryModal() {
            document.getElementById('subCategoryModal').classList.add('hidden');
            document.getElementById('subCategoryForm').reset();
            clearSubCategoryErrors();
            // Re-enable parent category dropdown when closing modal
            const parentSelect = document.getElementById('sub_cat_parent_id');
            parentSelect.disabled = false;
            parentSelect.classList.remove('bg-gray-100');
        }

        document.getElementById('categoryForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            // Include disabled regulatory body value in form data
            const regulatoryBody = document.getElementById('cat_regulatory_body');
            if (regulatoryBody.disabled) {
                formData.append('regulatory_body', regulatoryBody.value);
            }

            const response = await fetch('{{ route("admin.documents.categories.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                // Add new category to category_id select dropdown
                const select = document.getElementById('category_id');
                const option = document.createElement('option');
                option.value = data.category.id;
                option.textContent = data.category.name;
                option.setAttribute('data-regulatory-body', data.category.regulatory_body || '');
                select.appendChild(option);
                select.value = data.category.id;

                // Also add to sub_cat_parent_id dropdown (for sub-category modal)
                const parentSelect = document.getElementById('sub_cat_parent_id');
                if (parentSelect) {
                    const parentOption = document.createElement('option');
                    parentOption.value = data.category.id;
                    parentOption.textContent = data.category.name;
                    parentOption.setAttribute('data-regulatory-body', data.category.regulatory_body || '');
                    parentSelect.appendChild(parentOption);
                }

                // Trigger change event to filter sub-categories
                select.dispatchEvent(new Event('change'));

                closeCategoryModal();
                alert('Category added successfully!');
            } else {
                // Show validation errors in modal
                const errors = data.errors || {};
                if (data.message) {
                    errors.message = data.message;
                }
                showCategoryErrors(errors);
            }
        });

        document.getElementById('subCategoryForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            // Include disabled parent_id value in form data
            const parentSelect = document.getElementById('sub_cat_parent_id');
            if (parentSelect.disabled) {
                formData.append('parent_id', parentSelect.value);
            }

            const response = await fetch('{{ route("admin.documents.categories.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success && data.category.parent_id) {
                // Add new sub-category to sub-category select dropdown
                const subSelect = document.getElementById('sub_category_id');
                const subOption = document.createElement('option');
                subOption.value = data.category.id;
                subOption.textContent = data.category.name;
                subOption.setAttribute('data-parent', data.category.parent_id);
                // Get regulatory body from parent category
                const parentOption = document.getElementById('category_id').querySelector(`option[value="${data.category.parent_id}"]`);
                if (parentOption) {
                    subOption.setAttribute('data-regulatory-body', parentOption.dataset.regulatoryBody);
                }
                subSelect.appendChild(subOption);

                // Filter sub-categories based on selected category
                filterSubCategories();

                // Select the new sub-category if parent is selected
                const categoryId = document.getElementById('category_id').value;
                if (categoryId === data.category.parent_id) {
                    subSelect.value = data.category.id;
                }

                closeSubCategoryModal();
                alert('Sub Category added successfully!');
            } else {
                // Show validation errors in modal
                const errors = data.errors || {};
                if (data.message) {
                    errors.message = data.message;
                }
                showSubCategoryErrors(errors);
            }
        });
    </script>
</x-admin-layout>