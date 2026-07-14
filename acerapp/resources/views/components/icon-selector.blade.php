@props(['name' => 'icon', 'value' => '', 'label' => 'Icon'])

@php
    // Acericons list from CSS
    $acericons = [
        'acericon-innovation', 'acericon-integrity', 'acericon-accuracy', 'acericon-conectivity',
        'acericon-bank', 'acericon-book', 'acericon-bulding', 'acericon-check-in', 'acericon-check',
        'acericon-close', 'acericon-download-file', 'acericon-error', 'acericon-globale',
        'acericon-headphone', 'acericon-info', 'acericon-infrastructure', 'acericon-judge',
        'acericon-layer', 'acericon-loop', 'acericon-network', 'acericon-pie-chart',
        'acericon-pole', 'acericon-sad-user', 'acericon-secure', 'acericon-specker',
        'acericon-three-user', 'acericon-verified-user', 'acericon-up-arrow', 'acericon-menu',
        'acericon-arrow-forward', 'acericon-award-04', 'acericon-calendar', 'acericon-doc-check',
        'acericon-doc', 'acericon-double-tick', 'acericon-down-angle', 'acericon-download',
        'acericon-email', 'acericon-excellence', 'acericon-facebook', 'acericon-filter',
        'acericon-instagram', 'acericon-left-angle', 'acericon-linkedin', 'acericon-location-01',
        'acericon-methodology', 'acericon-office', 'acericon-phone', 'acericon-right-angle',
        'acericon-search', 'acericon-shield-blockchain', 'acericon-timer', 'acericon-transparency',
        'acericon-up-angle', 'acericon-user'
    ];
@endphp

<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
    </label>
    
    <!-- Selected Icon Preview -->
    @if($value)
        <div class="mb-3 border-2 border-gray-300 rounded-lg p-4 text-center bg-gray-50">
            <div class="w-16 h-16 bg-primary rounded-[12px] flex items-center justify-center mx-auto mb-2">
                <i class="{{ $value }} text-2xl text-white"></i>
            </div>
            <p class="text-sm text-gray-600">{{ $value }}</p>
        </div>
    @else
        <div class="mb-3 border-2 border-gray-300 rounded-lg p-4 text-center bg-gray-50">
            <div class="w-16 h-16 bg-gray-200 rounded-[12px] flex items-center justify-center mx-auto mb-2">
                <i class="acericon-doc text-3xl text-gray-400"></i>
            </div>
            <p class="text-sm text-gray-400">No icon selected</p>
        </div>
    @endif
    
    <!-- Icon Select Dropdown -->
    <select name="{{ $name }}" id="{{ $name }}" 
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
            onchange="updateIconPreview(this)">
        <option value="">-- Select Icon --</option>
        @foreach($acericons as $icon)
            <option value="{{ $icon }}" {{ $value === $icon ? 'selected' : '' }}>
                {{ str_replace('acericon-', '', $icon) }}
            </option>
        @endforeach
    </select>
    
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<script>
function updateIconPreview(selectElement) {
    const selectedValue = selectElement.value;
    const previewContainer = selectElement.previousElementSibling;
    
    if (selectedValue) {
        previewContainer.innerHTML = `
            <div class="w-16 h-16 bg-primary rounded-[12px] flex items-center justify-center mx-auto mb-2">
                <i class="${selectedValue} text-2xl text-white"></i>
            </div>
            <p class="text-sm text-gray-600">${selectedValue}</p>
        `;
    } else {
        previewContainer.innerHTML = `
            <div class="w-16 h-16 bg-gray-200 rounded-[12px] flex items-center justify-center mx-auto mb-2">
                <i class="acericon-doc text-3xl text-gray-400"></i>
            </div>
            <p class="text-sm text-gray-400">No icon selected</p>
        `;
    }
}
</script>

