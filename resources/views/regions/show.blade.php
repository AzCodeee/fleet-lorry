<x-layouts.app title="Region Detail" pageTitle="Fleet Management System">

    <div x-data="{ editOpen: false }">

        <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('regions.index') }}" class="hover:text-green-600 transition">Regions</a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <span class="text-gray-800 font-medium">{{ $region->name }}</span>
        </div>

        {{-- REGION CARD --}}
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <div class="flex flex-wrap justify-between items-start gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $region->name }}</h2>
                    @if($region->description)
                    <p class="text-sm text-gray-500 mt-1">{{ $region->description }}</p>
                    @endif
                </div>
                <div class="flex gap-2">
                    <x-btn type="outline" icon="pencil" size="sm" @click="editOpen = true">Edit</x-btn>
                    <form method="POST" action="{{ route('regions.destroy', $region) }}"
                          onsubmit="return confirm('Delete this region?')">
                        @csrf @method('DELETE')
                        <x-btn type="danger" icon="trash-2" size="sm" :submit="true">Delete</x-btn>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mt-6 text-sm">
                <div>
                    <p class="text-gray-500">Region Code</p>
                    <p class="font-semibold font-mono">{{ $region->code ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Total Sites</p>
                    <p class="font-semibold">{{ $region->sites->count() }} Sites</p>
                </div>
                <div>
                    <p class="text-gray-500">Created</p>
                    <p class="font-semibold">{{ $region->created_at?->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- SITES TABLE --}}
        <x-data-table title="Sites in this Region">
            <x-slot name="head">
                <th class="p-3 font-semibold text-gray-700">Site Name</th>
                <th class="p-3 font-semibold text-gray-700">Location</th>
                <th class="p-3 font-semibold text-gray-700">Type</th>
                <th class="p-3 text-right font-semibold text-gray-700">Action</th>
            </x-slot>

            @forelse($region->sites ?? [] as $site)
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3 font-medium text-gray-900">{{ $site->name }}</td>
                <td class="p-3">{{ $site->location }}</td>
                <td class="p-3"><x-badge :status="$site->type" /></td>
                <td class="p-3 text-right">
                    <x-btn href="{{ route('sites.show', $site) }}" type="outline" size="sm" icon="eye" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="p-8 text-center text-gray-400 italic text-sm">
                    No sites in this region.
                </td>
            </tr>
            @endforelse
        </x-data-table>

        {{-- EDIT OFFCANVAS --}}
        <x-offcanvas id="editOpen" title="Edit Region" saveText="Save Region">
            <x-slot name="body">
                <form id="editRegionForm" method="POST"
                      action="{{ route('regions.update', $region) }}" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-sm font-medium mb-1">Region Name</label>
                        <input type="text" name="name" value="{{ $region->name }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Code</label>
                        <input type="text" name="code" value="{{ $region->code }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea name="description" rows="3"
                                  class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ $region->description }}</textarea>
                    </div>
                </form>
            </x-slot>
            <x-slot name="footer">
                <x-btn type="secondary" @click="editOpen = false">Cancel</x-btn>
                <x-btn type="primary" @click="document.getElementById('editRegionForm').submit()">Save Region</x-btn>
            </x-slot>
        </x-offcanvas>

    </div>

</x-layouts.app>
