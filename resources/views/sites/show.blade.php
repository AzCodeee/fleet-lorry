<x-layouts.app title="Site Detail" pageTitle="Fleet Management System">

    <div x-data="{ editOpen: false }">

        <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('sites.index') }}" class="hover:text-green-600 transition">Sites</a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <span class="text-gray-800 font-medium">{{ $site->name }}</span>
        </div>

        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <div class="flex flex-wrap justify-between items-start gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $site->name }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $site->location }}</p>
                </div>
                <div class="flex gap-2">
                    <x-badge :status="$site->type" />
                    <x-badge :status="$site->status" />
                    <x-btn type="outline" icon="pencil" size="sm" @click="editOpen = true">Edit</x-btn>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mt-6 text-sm">
                <div>
                    <p class="text-gray-500">Region</p>
                    <p class="font-semibold">{{ $site->region->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Coordinates</p>
                    <p class="font-semibold font-mono text-xs">{{ $site->lat ?? '—' }}, {{ $site->lng ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Created</p>
                    <p class="font-semibold">{{ $site->created_at?->format('d M Y') }}</p>
                </div>
            </div>

            @if($site->notes)
            <div class="mt-4 pt-4 border-t text-sm text-gray-600">{{ $site->notes }}</div>
            @endif
        </div>

        {{-- RECENT TICKETS --}}
        <x-data-table title="Recent Tickets (This Site)">
            <x-slot name="head">
                <th class="p-3 font-semibold text-gray-700">Ticket ID</th>
                <th class="p-3 font-semibold text-gray-700">Role</th>
                <th class="p-3 font-semibold text-gray-700">Driver</th>
                <th class="p-3 font-semibold text-gray-700">Lorry</th>
                <th class="p-3 font-semibold text-gray-700">Status</th>
                <th class="p-3 text-right font-semibold text-gray-700">Action</th>
            </x-slot>

            @forelse($tickets ?? [] as $ticket)
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3 font-medium">{{ $ticket->ticket_number }}</td>
                <td class="p-3">
                    @if($ticket->loading_site_id === $site->id)
                        <x-badge status="Loading Site" />
                    @else
                        <x-badge status="Dumping Site" />
                    @endif
                </td>
                <td class="p-3">{{ $ticket->driver->name ?? '-' }}</td>
                <td class="p-3 font-mono text-xs">{{ $ticket->lorry->plate ?? '-' }}</td>
                <td class="p-3"><x-badge :status="$ticket->status" /></td>
                <td class="p-3 text-right">
                    <x-btn href="{{ route('tickets.show', $ticket) }}" type="outline" size="sm" icon="eye" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="p-8 text-center text-gray-400 italic text-sm">No tickets for this site.</td>
            </tr>
            @endforelse
        </x-data-table>

        {{-- EDIT OFFCANVAS --}}
        <x-offcanvas id="editOpen" title="Edit Site" saveText="Save Site">
            <x-slot name="body">
                <form id="editSiteForm" method="POST"
                      action="{{ route('sites.update', $site) }}" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-sm font-medium mb-1">Site Name</label>
                        <input type="text" name="name" value="{{ $site->name }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Location</label>
                        <input type="text" name="location" value="{{ $site->location }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Region</label>
                        <select name="region_id"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">— Select Region —</option>
                            @foreach($regions ?? [] as $region)
                                <option value="{{ $region->id }}" @selected($site->region_id === $region->id)>{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Type</label>
                        <select name="type"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            @foreach(['Loading Site','Dumping Site','Both'] as $t)
                                <option value="{{ $t }}" @selected($site->type === $t)>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select name="status"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="Active" @selected($site->status === 'Active')>Active</option>
                            <option value="Inactive" @selected($site->status === 'Inactive')>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Notes</label>
                        <textarea name="notes" rows="3"
                                  class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ $site->notes }}</textarea>
                    </div>
                </form>
            </x-slot>
            <x-slot name="footer">
                <x-btn type="secondary" @click="editOpen = false">Cancel</x-btn>
                <x-btn type="primary" @click="document.getElementById('editSiteForm').submit()">Save Site</x-btn>
            </x-slot>
        </x-offcanvas>

    </div>

</x-layouts.app>
