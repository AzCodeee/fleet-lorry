<x-layouts.app title="Regions" pageTitle="Fleet Management System">

    <div x-data="{ open: false }">

        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Regions</h2>
                <p class="text-sm text-gray-500 mt-1">Manage operational regions</p>
            </div>
            <x-btn type="primary" icon="plus" @click="open = true">Add Region</x-btn>
        </div>

        <x-data-table>
            <x-slot name="head">
                <th class="p-3 font-semibold text-gray-700">Region Name</th>
                <th class="p-3 font-semibold text-gray-700">Code</th>
                <th class="p-3 font-semibold text-gray-700">Total Sites</th>
                <th class="p-3 font-semibold text-gray-700">Created</th>
                <th class="p-3 text-right font-semibold text-gray-700">Action</th>
            </x-slot>

            @forelse($regions ?? [] as $region)
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3 font-medium text-gray-900">{{ $region->name }}</td>
                <td class="p-3 font-mono text-xs font-bold">{{ $region->code }}</td>
                <td class="p-3">
                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 text-xs font-medium">
                        {{ $region->sites_count ?? $region->sites->count() }} sites
                    </span>
                </td>
                <td class="p-3 text-sm text-gray-500">{{ $region->created_at?->format('d M Y') }}</td>
                <td class="p-3 text-right">
                    <x-btn href="{{ route('regions.show', $region) }}" type="outline" size="sm" icon="eye" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="p-8 text-center text-gray-400 italic text-sm">
                    No regions found. Click "Add Region" to create one.
                </td>
            </tr>
            @endforelse
        </x-data-table>

        {{-- ADD REGION OFFCANVAS --}}
        <x-offcanvas id="open" title="Add Region" saveText="Add Region">
            <x-slot name="body">
                <form id="addRegionForm" method="POST" action="{{ route('regions.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-1">Region Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Region Code</label>
                        <input type="text" name="code" placeholder="e.g. KCH"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea name="description" rows="3"
                                  class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                    </div>
                </form>
            </x-slot>
            <x-slot name="footer">
                <x-btn type="secondary" @click="open = false">Cancel</x-btn>
                <x-btn type="primary" @click="document.getElementById('addRegionForm').submit()">Add Region</x-btn>
            </x-slot>
        </x-offcanvas>

    </div>

</x-layouts.app>
