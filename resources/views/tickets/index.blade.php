<x-layouts.app title="Tickets" pageTitle="Fleet Management System">

    <div x-data="{ open: false, filter: '' }">

        {{-- PAGE HEADER --}}
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Tickets</h2>
                <p class="text-sm text-gray-500 mt-1">Track all fleet delivery tickets</p>
            </div>
            <x-btn type="primary" icon="plus" @click="open = true">Add Ticket</x-btn>
        </div>

        {{-- FILTER BAR --}}
        <div class="bg-white rounded-xl shadow p-4 mb-4 flex flex-wrap gap-3 items-center">
            <select x-model="filter"
                    class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">All Regions</option>
                @foreach($regions ?? [] as $region)
                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                @endforeach
            </select>

            <select class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                    name="status">
                <option value="">All Statuses</option>
                @foreach(['Pending','Ongoing','Completed','Delayed'] as $s)
                    <option value="{{ $s }}">{{ $s }}</option>
                @endforeach
            </select>

            <input type="text" placeholder="Search ticket, driver..."
                   class="border rounded-lg px-3 py-2 text-sm flex-1 min-w-[200px] focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        {{-- TABLE --}}
        <x-data-table>
            <x-slot name="head">
                <th class="p-3 font-semibold text-gray-700">Ticket ID</th>
                <th class="p-3 font-semibold text-gray-700">Project</th>
                <th class="p-3 font-semibold text-gray-700">Loading Site</th>
                <th class="p-3 font-semibold text-gray-700">Dumping Site</th>
                <th class="p-3 font-semibold text-gray-700">Driver</th>
                <th class="p-3 font-semibold text-gray-700">Lorry</th>
                <th class="p-3 font-semibold text-gray-700">Status</th>
                <th class="p-3 font-semibold text-gray-700">Priority</th>
                <th class="p-3 text-right font-semibold text-gray-700">Action</th>
            </x-slot>

            @forelse($tickets ?? [] as $ticket)
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3 font-medium text-gray-900">{{ $ticket->ticket_number }}</td>
                <td class="p-3">{{ $ticket->project->name ?? '-' }}</td>
                <td class="p-3">{{ $ticket->loadingSite->name ?? '-' }}</td>
                <td class="p-3">{{ $ticket->dumpingSite->name ?? '-' }}</td>
                <td class="p-3">{{ $ticket->driver->name ?? '-' }}</td>
                <td class="p-3 font-mono text-xs font-medium">{{ $ticket->lorry->plate ?? '-' }}</td>
                <td class="p-3"><x-badge :status="$ticket->status" /></td>
                <td class="p-3"><x-badge :status="$ticket->priority" /></td>
                <td class="p-3 text-right">
                    <x-btn href="{{ route('tickets.show', $ticket) }}" type="outline" size="sm" icon="eye" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="p-8 text-center text-gray-400 italic text-sm">
                    No tickets found. Click "Add Ticket" to create one.
                </td>
            </tr>
            @endforelse
        </x-data-table>

        @if(isset($tickets) && $tickets->hasPages())
        <div class="mt-4">{{ $tickets->links() }}</div>
        @endif

        {{-- ADD TICKET OFFCANVAS --}}
        <x-offcanvas id="open" title="Add Ticket" saveText="Create Ticket">
            <x-slot name="body">
                <form id="addTicketForm" method="POST" action="{{ route('tickets.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-1">Project <span class="text-red-500">*</span></label>
                        <select name="project_id" required
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">— Select Project —</option>
                            @foreach($projects ?? [] as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Loading Site <span class="text-red-500">*</span></label>
                        <select name="loading_site_id" required
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">— Select Site —</option>
                            @foreach($sites ?? [] as $site)
                                <option value="{{ $site->id }}">{{ $site->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Dumping Site <span class="text-red-500">*</span></label>
                        <select name="dumping_site_id" required
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">— Select Site —</option>
                            @foreach($sites ?? [] as $site)
                                <option value="{{ $site->id }}">{{ $site->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Driver</label>
                        <select name="driver_id"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">— Assign Driver —</option>
                            @foreach($drivers ?? [] as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Lorry</label>
                        <select name="lorry_id"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">— Assign Lorry —</option>
                            @foreach($lorries ?? [] as $lorry)
                                <option value="{{ $lorry->id }}">{{ $lorry->plate }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Priority</label>
                        <select name="priority"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                        </select>
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
                <x-btn type="primary" @click="document.getElementById('addTicketForm').submit()">Create Ticket</x-btn>
            </x-slot>
        </x-offcanvas>

    </div>

</x-layouts.app>
