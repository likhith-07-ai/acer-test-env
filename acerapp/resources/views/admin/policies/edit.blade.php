<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Edit Policy</h1>
        </div>

        <form method="POST" action="{{ route('admin.policies.update', $policy) }}" enctype="multipart/form-data"
            id="policyForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content Column -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Title <span class="text-red-800">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $policy->title) }}" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            placeholder="Enter policy title...">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content Editor -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            Content <span class="text-red-800">*</span>
                        </label>
                        <textarea name="content" id="content"
                            class="tinymce-editor">{{ old('content', $policy->content) }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tagline -->
                    <div>
                        <label for="tagline" class="block text-sm font-medium text-gray-700 mb-2">
                            Tagline / Quote
                        </label>
                        <textarea name="tagline" id="tagline" rows="3"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            placeholder="Optional one-line summary or quote...">{{ old('tagline', $policy->tagline) }}</textarea>
                        @error('tagline')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Sidebar Column -->
                <div class="space-y-6">
                    <!-- Icon Selector -->
                    <x-icon-selector name="icon" :value="old('icon', $policy->icon)" label="Icon" />
                    @error('icon')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-800">*</span>
                        </label>
                        <select name="status" id="status" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="draft" {{ old('status', $policy->status) == 'draft' ? 'selected' : '' }}>Draft
                            </option>
                            <option value="published" {{ old('status', $policy->status) == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Restricted Access -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_restricted" value="1" {{ old('is_restricted', $policy->is_restricted) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">Restricted Access</span>
                        </label>
                        <p class="mt-1 text-xs text-gray-500">Restricted policies are only visible to admins</p>
                    </div>

                    <!-- PDF File Upload -->
                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                            PDF Document
                        </label>
                        @if($policy->file_path)
                            @php
                                $extension = pathinfo($policy->file_path, PATHINFO_EXTENSION);
                                $safeTitle = preg_replace('/[^A-Za-z0-9\-_]/', '_', $policy->title);
                                $safeTitle = preg_replace('/_+/', '_', $safeTitle);
                                $safeTitle = trim($safeTitle, '_');
                                $downloadFileName = $safeTitle . '_' . $policy->id . '.' . $extension;
                            @endphp
                            <div class="mb-2 p-2 bg-gray-50 rounded border">
                                <p class="text-sm text-gray-700">Current file: <a
                                        href="{{ route('admin.policies.download', $policy) }}"
                                        download="{{ $downloadFileName }}" class="text-primary-600 hover:underline">Download
                                        PDF</a></p>
                            </div>
                        @endif
                        <input type="file" name="file" id="file" accept=".pdf"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        <p class="mt-1 text-xs text-gray-500">Leave empty to keep current file</p>
                        @error('file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex space-x-3 pt-4">
                        <button type="submit"
                            class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 px-4 rounded-md transition-colors">
                            Update Policy
                        </button>
                        <a href="{{ route('admin.policies.index') }}"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-md text-center transition-colors">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- TinyMCE Script (Free CDN - No License Required) -->
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>

    <script>
        tinymce.init({
            selector: '.tinymce-editor',
            height: 500,
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
</x-admin-layout>