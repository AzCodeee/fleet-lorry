<x-layouts.app title="Sites" pageTitle="Fleet Management System">

    <div x-data="{ open: false }">

        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Sites</h2>
                <p class="text-sm text-gray-500 mt-1">Manage loading & dumping sites</p>
            </div>
            <x-btn type="primary" icon="plus" @click="open = true">Add Site</x-btn>
        </div>

        <x-data-table>
            <x-slot name="head">
                <th class="p-3 font-semibold text-gray-700">Site Name</th>
                <th class="p-3 font-semibold text-gray-700">Location</th>
                <th class="p-3 font-semibold text-gray-700">Region</th>
                <th class="p-3 font-semibold text-gray-700">Type</th>
                <th class="p-3 font-semibold text-gray-700">Status</th>
                <th class="p-3 text-right font-semibold text-gray-700">Action</th>
            </x-slot>

            @forelse($sites ?? [] as $site)
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3 font-medium text-gray-900">{{ $site->name }}</td>
                <td class="p-3">{{ $site->location }}</td>
                <td class="p-3">{{ $site->region->name ?? '—' }}</td>
                <td class="p-3"><x-badge :status="$site->type" /></td>
                <td class="p-3"><x-badge :status="$site->status" /></td>
                <td class="p-3 text-right">
                    <x-btn href="{{ route('sites.show', $site) }}" type="outline" size="sm" icon="eye" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="p-8 text-center text-gray-400 italic text-sm">
                    No sites found. Click "Add Site" to create one.
                </td>
            </tr>
            @endforelse
        </x-data-table>

        @if(isset($sites) && $sites->hasPages())
        <div class="mt-4">{{ $sites->links() }}</div>
        @endif

        {{-- ADD SITE OFFCANVAS --}}
        <x-offcanvas id="open" title="Add Site" saveText="Add Site">
            <x-slot name="body">
                <form id="addSiteForm" method="POST" action="{{ route('sites.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-1">Site Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Location / Address</label>
                        <input type="text" name="location"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Region</label>
                        <select name="region_id"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">— Select Region —</option>
                            @foreach($regions ?? [] as $region)
                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Type</label>
                        <select name="type"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="Loading Site">Loading Site</option>
                            <option value="Dumping Site">Dumping Site</option>
                            <option value="Both">Both</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select name="status"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Coordinates (optional)</label>
                        <div class="grid grid-cols-2 gap-3">
                            <input type="text" name="lat" placeholder="Latitude"
                                   class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                            <input type="text" name="lng" placeholder="Longitude"
                                   class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Notes</label>
                        <textarea name="notes" rows="3"
                                  class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                    </div>
                </form>
            </x-slot>
            <x-slot name="footer">
                <x-btn type="secondary" @click="open = false">Cancel</x-btn>
                <x-btn type="primary" @click="document.getElementById('addSiteForm').submit()">Add Site</x-btn>
            </x-slot>
        </x-offcanvas>

    </div>

</x-layouts.app>
