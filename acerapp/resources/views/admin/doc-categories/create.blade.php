<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Create Document Category</h1>

        <div class="bg-white shadow rounded-lg p-6">
            <form method="POST" action="{{ route('admin.doc-categories.store') }}">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Category Name <span class="text-red-800">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
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
                            <option value="SEBI" {{ old('regulatory_body') == 'SEBI' ? 'selected' : '' }}>SEBI</option>
                            <option value="RBI" {{ old('regulatory_body') == 'RBI' ? 'selected' : '' }}>RBI</option>
                            <option value="OTHER" {{ old('regulatory_body') == 'OTHER' ? 'selected' : '' }}>OTHER</option>
                        </select>
                        @error('regulatory_body')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="short_description" class="block text-sm font-medium text-gray-700">Short Description</label>
                        <textarea name="short_description" id="short_description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('short_description') }}</textarea>
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
                                {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
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
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
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