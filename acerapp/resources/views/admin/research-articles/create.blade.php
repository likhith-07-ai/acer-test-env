<x-admin-layout mainInnerWrapper="p-0">
    <div class="min-h-full">
        <form method="POST" action="{{ route('admin.research-articles.store') }}" enctype="multipart/form-data"
            id="articleForm">
            @csrf

            <!-- Two Column Layout -->
            <div class="flex gap-6 h-[calc(100vh-170px)]">
                <!-- Left Column - Title & Content Editor -->
                <div class="flex-1 flex flex-col border-r border-gray-200 pr-6 min-w-0">
                    <div class="flex-1 flex flex-col overflow-hidden">
                        <!-- Title -->
                        <div class="mb-6 flex-shrink-0">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Title <span class="text-red-800">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-lg font-semibold"
                                placeholder="Enter article title...">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content Editor -->
                        <div class="flex-1 flex flex-col min-h-0">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                Content <span class="text-red-800">*</span>
                            </label>
                            <textarea name="content" id="content" class="tinymce-editor"
                                required>{{ old('content') }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Right Column - Sidebar Fields -->
                <div class="w-[300px] flex-shrink-0 flex flex-col overflow-y-auto">
                    <div class="space-y-6">
                        <!-- Excerpt -->
                        <div>
                            <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                                Excerpt
                            </label>
                            <textarea name="excerpt" id="excerpt" rows="4" maxlength="500"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                placeholder="Brief description of the article...">{{ old('excerpt') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Maximum 500 characters</p>
                            @error('excerpt')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="category_id" class="block text-sm font-medium text-gray-700">
                                    Category
                                </label>
                                <button type="button" onclick="openCategoryModal()"
                                    class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                    + Add
                                </button>
                            </div>
                            <select name="category_id" id="category_id"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Featured Image -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Featured Image
                            </label>
                            <div id="imageDropZone"
                                class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary-400 transition-colors cursor-pointer">
                                <input type="file" name="featured_image" id="featured_image" accept="image/*"
                                    class="hidden">
                                <div id="imagePlaceholder" class="space-y-2">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="text-sm text-gray-600">Drag & drop image here</p>
                                    <p class="text-xs text-gray-500">or click to browse</p>
                                </div>
                                <div id="imagePreview" class="hidden">
                                    <img id="previewImg" src="" alt="Preview" class="max-w-full h-auto rounded-lg mb-2">
                                    <button type="button" onclick="removeImage()"
                                        class="text-sm text-red-600 hover:text-red-800">
                                        Remove Image
                                    </button>
                                </div>
                            </div>
                            @error('featured_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tags -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="tags" class="block text-sm font-medium text-gray-700">
                                    Tags
                                </label>
                                <button type="button" onclick="openTagModal()"
                                    class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                    + Add
                                </button>
                            </div>
                            <select name="tags[]" id="tags" multiple
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Hold Ctrl/Cmd to select multiple</p>
                            @error('tags.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-800">*</span>
                            </label>
                            <select name="status" id="status" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft
                                </option>
                                <option value="submitted" {{ old('status') == 'submitted' ? 'selected' : '' }}>Submit for
                                    Review</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Restricted Access -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_restricted" value="1" {{ old('is_restricted') ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <span class="ml-2 text-sm text-gray-700">Restricted Access</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-500">Only visible to admin users</p>
                        </div>

                        <!-- SEO Meta -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-sm font-semibold text-gray-700 mb-4">SEO Settings</h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Meta Description
                                    </label>
                                    <textarea name="meta_description" id="meta_description" rows="3"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        placeholder="SEO meta description...">{{ old('meta_description') }}</textarea>
                                    <p class="mt-1 text-sm text-gray-500">Recommended: 150-160 characters</p>
                                </div>

                                <div>
                                    <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">
                                        Meta Keywords
                                    </label>
                                    <input type="text" name="meta_keywords" id="meta_keywords"
                                        value="{{ old('meta_keywords') }}"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                        placeholder="keyword1, keyword2, keyword3">
                                    <p class="mt-1 text-sm text-gray-500">Comma-separated keywords</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-6 flex items-center justify-end space-x-4 border-t border-gray-200 pt-6">
                <a href="{{ route('admin.research-articles.index') }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Cancel
                </a>
                <button type="submit"
                    class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
                    Create Article
                </button>
            </div>
        </form>
    </div>

    <!-- Category Management Modal -->
    <div id="categoryModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add Research Category</h3>
                <form id="categoryForm">
                    @csrf
                    <div class="mb-4">
                        <label for="cat_name" class="block text-sm font-medium text-gray-700">Category Name <span
                                class="text-red-800">*</span></label>
                        <input type="text" name="name" id="cat_name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label for="cat_description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="cat_description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" checked
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
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

    <!-- Tag Management Modal -->
    <div id="tagModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add Research Tag</h3>
                <form id="tagForm">
                    @csrf
                    <div class="mb-4">
                        <label for="tag_name" class="block text-sm font-medium text-gray-700">Tag Name <span
                                class="text-red-800">*</span></label>
                        <input type="text" name="name" id="tag_name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label for="tag_description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="tag_description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" onclick="closeTagModal()"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Add Tag
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TinyMCE Script (Free CDN - No License Required) -->
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>

    <script>
        tinymce.init({
            selector: '.tinymce-editor',
            height: 600,
            menubar: true,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic forecolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
            promotion: false,
            branding: false
        });
    </script>

    <script>
        // Image Drag and Drop
        document.addEventListener('DOMContentLoaded', function () {
            const dropZone = document.getElementById('imageDropZone');
            const fileInput = document.getElementById('featured_image');
            const imagePlaceholder = document.getElementById('imagePlaceholder');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');

            if (dropZone && fileInput) {
                dropZone.addEventListener('click', () => fileInput.click());

                dropZone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropZone.classList.add('border-primary-500', 'bg-primary-50');
                });

                dropZone.addEventListener('dragleave', () => {
                    dropZone.classList.remove('border-primary-500', 'bg-primary-50');
                });

                dropZone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropZone.classList.remove('border-primary-500', 'bg-primary-50');

                    const files = e.dataTransfer.files;
                    if (files.length > 0 && files[0].type.startsWith('image/')) {
                        handleImageFile(files[0]);
                    }
                });

                fileInput.addEventListener('change', (e) => {
                    if (e.target.files.length > 0) {
                        handleImageFile(e.target.files[0]);
                    }
                });

                function handleImageFile(file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewImg.src = e.target.result;
                        imagePlaceholder.classList.add('hidden');
                        imagePreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }

                window.removeImage = function () {
                    fileInput.value = '';
                    previewImg.src = '';
                    imagePlaceholder.classList.remove('hidden');
                    imagePreview.classList.add('hidden');
                };
            }

            // Modal functions
            window.openCategoryModal = function () {
                document.getElementById('categoryModal').classList.remove('hidden');
            };

            window.closeCategoryModal = function () {
                document.getElementById('categoryModal').classList.add('hidden');
                document.getElementById('categoryForm').reset();
            };

            window.openTagModal = function () {
                document.getElementById('tagModal').classList.remove('hidden');
            };

            window.closeTagModal = function () {
                document.getElementById('tagModal').classList.add('hidden');
                document.getElementById('tagForm').reset();
            };

            // Category form submission
            const categoryForm = document.getElementById('categoryForm');
            if (categoryForm) {
                categoryForm.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const response = await fetch('{{ route("admin.research-articles.categories.store") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        const select = document.getElementById('category_id');
                        const option = document.createElement('option');
                        option.value = data.category.id;
                        option.textContent = data.category.name;
                        select.appendChild(option);
                        select.value = data.category.id;
                        closeCategoryModal();
                        alert('Category added successfully!');
                    } else {
                        alert('Error: ' + (data.message || JSON.stringify(data.errors || 'Failed to add category')));
                    }
                });
            }

            // Tag form submission
            const tagForm = document.getElementById('tagForm');
            if (tagForm) {
                tagForm.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const response = await fetch('{{ route("admin.research-articles.tags.store") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        const select = document.getElementById('tags');
                        const option = document.createElement('option');
                        option.value = data.tag.id;
                        option.textContent = data.tag.name;
                        option.selected = true;
                        select.appendChild(option);
                        closeTagModal();
                        alert('Tag added successfully!');
                    } else {
                        alert('Error: ' + (data.message || JSON.stringify(data.errors || 'Failed to add tag')));
                    }
                });
            }
        });
    </script>
    </script>
</x-admin-layout>