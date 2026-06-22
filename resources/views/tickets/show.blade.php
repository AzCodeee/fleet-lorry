<x-layouts.app title="Ticket Detail" pageTitle="Fleet Management System">

    <div x-data="{ editOpen: false }">

        {{-- BREADCRUMB --}}
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('tickets.index') }}" class="hover:text-green-600 transition">Tickets</a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <span class="text-gray-800 font-medium">{{ $ticket->ticket_number }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- MAIN DETAIL --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- TICKET CARD --}}
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $ticket->ticket_number }}</h2>
                            <p class="text-sm text-gray-500 mt-1">{{ $ticket->project->name ?? 'No Project' }}</p>
                        </div>
                        <div class="flex gap-2 items-center">
                            <x-badge :status="$ticket->priority" />
                            <x-badge :status="$ticket->status" />
                            <x-btn type="outline" icon="pencil" size="sm" @click="editOpen = true">Edit</x-btn>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6 text-sm">
                        <div>
                            <p class="text-gray-500">Loading Site</p>
                            <p class="font-semibold mt-1">{{ $ticket->loadingSite->name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Dumping Site</p>
                            <p class="font-semibold mt-1">{{ $ticket->dumpingSite->name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Driver</p>
                            <p class="font-semibold mt-1">
                                @if($ticket->driver)
                                    <a href="{{ route('drivers.show', $ticket->driver) }}"
                                       class="text-green-600 hover:underline">{{ $ticket->driver->name }}</a>
                                @else —
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500">Lorry</p>
                            <p class="font-semibold font-mono mt-1">
                                @if($ticket->lorry)
                                    <a href="{{ route('lorries.show', $ticket->lorry) }}"
                                       class="text-green-600 hover:underline">{{ $ticket->lorry->plate }}</a>
                                @else —
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500">Created</p>
                            <p class="font-semibold mt-1">{{ $ticket->created_at?->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Updated</p>
                            <p class="font-semibold mt-1">{{ $ticket->updated_at?->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    @if($ticket->notes)
                    <div class="mt-6 pt-4 border-t text-sm">
                        <p class="text-gray-500 mb-1">Notes</p>
                        <p class="text-gray-700">{{ $ticket->notes }}</p>
                    </div>
                    @endif
                </div>

                {{-- STATUS TIMELINE --}}
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="font-semibold mb-4">Status History</h3>
                    <div class="space-y-3">
                        @forelse($ticket->statusLogs ?? [] as $log)
                        <div class="flex items-start gap-3 text-sm">
                            <div class="w-2 h-2 rounded-full bg-green-500 mt-1.5 shrink-0"></div>
                            <div>
                                <span class="font-medium">{{ $log->status }}</span>
                                <span class="text-gray-400 ml-2">{{ $log->created_at?->diffForHumans() }}</span>
                                @if($log->note)
                                <p class="text-gray-500 mt-0.5">{{ $log->note }}</p>
                                @endif
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-400 italic text-sm">No status history yet.</p>
                        @endforelse
                    </div>
                </div>

            </div>

            {{-- SIDEBAR INFO --}}
            <div class="space-y-4">

                {{-- QUICK UPDATE STATUS --}}
                <div class="bg-white rounded-xl shadow p-4">
                    <h3 class="font-semibold mb-3 text-sm">Update Status</h3>
                    <form method="POST" action="{{ route('tickets.update', $ticket) }}" class="space-y-3">
                        @csrf @method('PATCH')
                        <select name="status"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                            @foreach(['Pending','Ongoing','Completed','Delayed'] as $s)
                                <option value="{{ $s }}" @selected($ticket->status === $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                        <x-btn type="primary" :submit="true" class="w-full justify-center">Update</x-btn>
                    </form>
                </div>

                {{-- LORRY CARD --}}
                @if($ticket->lorry)
                <div class="bg-white rounded-xl shadow p-4 text-sm">
                    <h3 class="font-semibold mb-3">Lorry</h3>
                    <p class="font-mono font-bold text-gray-900">{{ $ticket->lorry->plate }}</p>
                    <p class="text-gray-500 mt-1">{{ $ticket->lorry->bucket_size }}</p>
                    <x-badge :status="$ticket->lorry->status" />
                    <div class="mt-3">
                        <x-btn href="{{ route('lorries.show', $ticket->lorry) }}" type="outline" size="sm" icon="eye">View Lorry</x-btn>
                    </div>
                </div>
                @endif

                {{-- DRIVER CARD --}}
                @if($ticket->driver)
                <div class="bg-white rounded-xl shadow p-4 text-sm">
                    <h3 class="font-semibold mb-3">Driver</h3>
                    <p class="font-bold text-gray-900">{{ $ticket->driver->name }}</p>
                    <p class="text-gray-500 mt-1">{{ $ticket->driver->phone }}</p>
                    <div class="mt-3">
                        <x-btn href="{{ route('drivers.show', $ticket->driver) }}" type="outline" size="sm" icon="eye">View Driver</x-btn>
                    </div>
                </div>
                @endif

            </div>
        </div>

        {{-- EDIT OFFCANVAS --}}
        <x-offcanvas id="editOpen" title="Edit Ticket" saveText="Save Changes">
            <x-slot name="body">
                <form id="editTicketForm" method="POST"
                      action="{{ route('tickets.update', $ticket) }}" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-sm font-medium mb-1">Priority</label>
                        <select name="priority"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            @foreach(['Low','Medium','High'] as $p)
                                <option value="{{ $p }}" @selected($ticket->priority === $p)>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Driver</label>
                        <select name="driver_id"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">— None —</option>
                            @foreach($drivers ?? [] as $driver)
                                <option value="{{ $driver->id }}" @selected($ticket->driver_id === $driver->id)>{{ $driver->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Lorry</label>
                        <select name="lorry_id"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">— None —</option>
                            @foreach($lorries ?? [] as $lorry)
                                <option value="{{ $lorry->id }}" @selected($ticket->lorry_id === $lorry->id)>{{ $lorry->plate }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Notes</label>
                        <textarea name="notes" rows="4"
                                  class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ $ticket->notes }}</textarea>
                    </div>
                </form>
            </x-slot>
            <x-slot name="footer">
                <x-btn type="secondary" @click="editOpen = false">Cancel</x-btn>
                <x-btn type="primary" @click="document.getElementById('editTicketForm').submit()">Save Changes</x-btn>
            </x-slot>
        </x-offcanvas>

    </div>

</x-layouts.app>
