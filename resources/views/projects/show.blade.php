<x-layouts.app title="Project Detail" pageTitle="Fleet Management System">

    <div x-data="{ editOpen: false, addTicketOpen: false }">

        {{-- BREADCRUMB --}}
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('projects.index') }}" class="hover:text-green-600 transition">Projects</a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <span class="text-gray-800 font-medium">{{ $project->name }}</span>
        </div>

        {{-- DETAIL CARD --}}
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <div class="flex flex-wrap justify-between items-start gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $project->name }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $project->client }}</p>
                </div>
                <div class="flex gap-2">
                    <x-btn type="outline" icon="pencil" @click="editOpen = true">Edit</x-btn>
                    <x-badge :status="$project->status" />
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 text-sm">
                <div>
                    <p class="text-gray-500">Region</p>
                    <p class="font-semibold">{{ $project->region->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Start Date</p>
                    <p class="font-semibold">{{ $project->start_date?->format('d M Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">End Date</p>
                    <p class="font-semibold">{{ $project->end_date?->format('d M Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Total Tickets</p>
                    <p class="font-semibold">{{ $project->tickets->count() }}</p>
                </div>
            </div>

            @if($project->description)
            <div class="mt-4 text-sm text-gray-600 border-t pt-4">
                {{ $project->description }}
            </div>
            @endif
        </div>

        {{-- TICKETS TABLE --}}
        <x-data-table title="Tickets">
            <x-slot name="actions">
                <x-btn type="primary" icon="plus" @click="addTicketOpen = true">Add Ticket</x-btn>
            </x-slot>
            <x-slot name="head">
                <th class="p-3 font-semibold text-gray-700">Ticket ID</th>
                <th class="p-3 font-semibold text-gray-700">Loading Site</th>
                <th class="p-3 font-semibold text-gray-700">Dumping Site</th>
                <th class="p-3 font-semibold text-gray-700">Driver</th>
                <th class="p-3 font-semibold text-gray-700">Lorry</th>
                <th class="p-3 font-semibold text-gray-700">Status</th>
                <th class="p-3 font-semibold text-gray-700">Priority</th>
                <th class="p-3 text-right font-semibold text-gray-700">Action</th>
            </x-slot>

            @forelse($project->tickets ?? [] as $ticket)
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3 font-medium">{{ $ticket->ticket_number }}</td>
                <td class="p-3">{{ $ticket->loadingSite->name ?? '-' }}</td>
                <td class="p-3">{{ $ticket->dumpingSite->name ?? '-' }}</td>
                <td class="p-3">{{ $ticket->driver->name ?? '-' }}</td>
                <td class="p-3 font-mono text-xs">{{ $ticket->lorry->plate ?? '-' }}</td>
                <td class="p-3"><x-badge :status="$ticket->status" /></td>
                <td class="p-3"><x-badge :status="$ticket->priority" /></td>
                <td class="p-3 text-right">
                    <x-btn href="{{ route('tickets.show', $ticket) }}" type="outline" size="sm" icon="eye" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="p-8 text-center text-gray-400 italic text-sm">
                    No tickets. Click "Add Ticket" to create one.
                </td>
            </tr>
            @endforelse
        </x-data-table>

        {{-- EDIT PROJECT OFFCANVAS --}}
        <x-offcanvas id="editOpen" title="Edit Project" saveText="Save Changes">
            <x-slot name="body">
                <form id="editProjectForm" method="POST"
                      action="{{ route('projects.update', $project) }}" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-sm font-medium mb-1">Project Name</label>
                        <input type="text" name="name" value="{{ $project->name }}" required
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Client</label>
                        <input type="text" name="client" value="{{ $project->client }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select name="status" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            @foreach(['Active','Pending','Completed','Cancelled'] as $s)
                                <option value="{{ $s }}" @selected($project->status === $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Start Date</label>
                            <input type="date" name="start_date" value="{{ $project->start_date?->format('Y-m-d') }}"
                                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">End Date</label>
                            <input type="date" name="end_date" value="{{ $project->end_date?->format('Y-m-d') }}"
                                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea name="description" rows="3"
                                  class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ $project->description }}</textarea>
                    </div>
                </form>
            </x-slot>
            <x-slot name="footer">
                <x-btn type="secondary" @click="editOpen = false">Cancel</x-btn>
                <x-btn type="primary" @click="document.getElementById('editProjectForm').submit()">Save Changes</x-btn>
            </x-slot>
        </x-offcanvas>

    </div>

</x-layouts.app>
