<x-admin-layout>
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-900">Press Releases</h1>
            @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('press-releases.create'))
                <a href="{{ route('admin.press-releases.create') }}"
                    class="flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-4 rounded-lg shadow-sm">
                    Create Press Release
                </a>
            @endif
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200 border-collapse">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Company Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Headline</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pressReleases as $pressRelease)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $pressRelease->date ? $pressRelease->date->format('d M Y') : '' }}
                            </td>
                            <td class="px-6 py-4">{{ $pressRelease->company_name }}</td>
                            <td class="px-6 py-4">{{ $pressRelease->headline }}</td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end items-center gap-3">
                                @if($pressRelease->format === 'pdf')
                                    <button type="button"
                                        onclick="window.open('{{ route('public.pdf.viewer', ['type' => 'press-release', 'id' => $pressRelease->id]) }}', 'PressReleaseWindow', 'width=1000,height=800,menubar=no,toolbar=no,location=no,status=no')"
                                        class="text-red-600 hover:text-red-900" title="View PDF">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 256 256">
                                            <path
                                                d="M224,152a8,8,0,0,1-8,8H192v16h16a8,8,0,0,1,0,16H192v16a8,8,0,0,1-16,0V152a8,8,0,0,1,8-8h32A8,8,0,0,1,224,152ZM92,172a28,28,0,0,1-28,28H56v8a8,8,0,0,1-16,0V152a8,8,0,0,1,8-8H64A28,28,0,0,1,92,172Zm-16,0a12,12,0,0,0-12-12H56v24h8A12,12,0,0,0,76,172Zm88,8a36,36,0,0,1-36,36H112a8,8,0,0,1-8-8V152a8,8,0,0,1,8-8h16A36,36,0,0,1,164,180Zm-16,0a20,20,0,0,0-20-20h-8v40h8A20,20,0,0,0,148,180ZM40,112V40A16,16,0,0,1,56,24h96a8,8,0,0,1,5.66,2.34l56,56A8,8,0,0,1,216,88v24a8,8,0,0,1-16,0V96H152a8,8,0,0,1-8-8V40H56v72a8,8,0,0,1-16,0ZM160,80h28.69L160,51.31Z">
                                            </path>
                                        </svg>
                                    </button>
                                @else
                                    <button type="button"
                                        onclick="window.open('{{ route('public.press-releases.show', $pressRelease->id) }}', 'PressReleaseWindow', 'width=1000,height=800,menubar=no,toolbar=no,location=no,status=no')"
                                        class="text-blue-600 hover:text-blue-900" title="View HTML">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 256 256">
                                            <path
                                                d="M216,120V88a8,8,0,0,0-2.34-5.66l-56-56A8,8,0,0,0,152,24H56A16,16,0,0,0,40,40v80a8,8,0,0,0,16,0V40h88V88a8,8,0,0,0,8,8h48v24a8,8,0,0,0,16,0ZM160,51.31,188.69,80H160ZM68,160v48a8,8,0,0,1-16,0V192H32v16a8,8,0,0,1-16,0V160a8,8,0,0,1,16,0v16H52V160a8,8,0,0,1,16,0Zm56,0a8,8,0,0,1-8,8h-8v40a8,8,0,0,1-16,0V168H84a8,8,0,0,1,0-16h32A8,8,0,0,1,124,160Zm72,0v48a8,8,0,0,1-16,0V184l-9.6,12.8a8,8,0,0,1-12.8,0L148,184v24a8,8,0,0,1-16,0V160a8,8,0,0,1,14.4-4.8L164,178.67l17.6-23.47A8,8,0,0,1,196,160Zm56,48a8,8,0,0,1-8,8H216a8,8,0,0,1-8-8V160a8,8,0,0,1,16,0v40h20A8,8,0,0,1,252,208Z">
                                            </path>
                                        </svg>
                                    </button>
                                @endif
                                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('press-releases.edit'))
                                    <a href="{{ route('admin.press-releases.edit', $pressRelease) }}"
                                        class="text-gray-600 hover:text-gray-900" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                @endif
                                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('press-releases.delete'))
                                    <form action="{{ route('admin.press-releases.destroy', $pressRelease) }}" method="POST"
                                        class="inline flex items-center">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete"
                                            onclick="return confirm('Are you sure you want to delete this press release?');">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">No press releases found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pressReleases->hasPages())
            <div class="mt-4">
                {{ $pressReleases->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>