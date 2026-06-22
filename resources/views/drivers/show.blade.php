<x-layouts.app title="Driver Detail" pageTitle="Fleet Management System">

    <div x-data="{ editOpen: false, activeTab: 'all' }">

        <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('drivers.index') }}" class="hover:text-green-600 transition">Drivers</a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <span class="text-gray-800 font-medium">{{ $driver->name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT: DRIVER PROFILE --}}
            <div class="lg:col-span-1 space-y-4">

                <div class="bg-white rounded-xl shadow p-6">

                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xl">
                                {{ strtoupper(substr($driver->name, 0, 1)) }}
                            </div>
                            <div>
                                <h2 class="font-bold text-gray-900">{{ $driver->name }}</h2>
                                <p class="text-xs text-gray-500">{{ $driver->employee_id }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <x-badge :status="$driver->status" />
                            <button @click="editOpen = true"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 hover:bg-white hover:shadow transition border border-gray-200">
                                <i data-lucide="pencil" class="w-4 h-4 text-gray-600"></i>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-500">License No.</span>
                            <span class="font-medium font-mono">{{ $driver->license_number ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-500">License Expiry</span>
                            <span class="font-medium {{ $driver->license_expiry?->isPast() ? 'text-red-600' : '' }}">
                                {{ $driver->license_expiry?->format('d M Y') ?? '—' }}
                            </span>
                        </div>
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-500">Certification</span>
                            <span class="font-medium">{{ $driver->certification ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-500">Phone</span>
                            <span class="font-medium">{{ $driver->phone ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-500">Assigned Lorry</span>
                            @if($driver->lorry)
                                <a href="{{ route('lorries.show', $driver->lorry) }}"
                                   class="font-medium font-mono text-green-600 hover:underline">
                                    {{ $driver->lorry->plate }}
                                </a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </div>
                    </div>

                    @if($driver->notes)
                    <div class="mt-4 pt-4 border-t text-sm text-gray-600">
                        {{ $driver->notes }}
                    </div>
                    @endif

                </div>

                {{-- COMPLETED TRIPS SUMMARY --}}
                <div class="bg-emerald-50 rounded-xl shadow p-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Completed Trips</p>
                        <p class="text-2xl font-bold text-emerald-700">
                            {{ $driver->tickets->where('status', 'Completed')->count() }}
                        </p>
                    </div>
                </div>

            </div>

            {{-- RIGHT: TICKETS --}}
            <div class="lg:col-span-2">

                <div class="bg-white rounded-xl shadow overflow-hidden">

                    <div class="p-5 border-b">
                        <h3 class="font-semibold">Trips & Tickets</h3>
                        <p class="text-sm text-gray-500 mt-1">Ticket history for this driver</p>
                    </div>

                    {{-- TABS --}}
                    <div class="flex border-b px-5 pt-2 gap-1">
                        @foreach([['all','All'],['Completed','Completed'],['Pending','Pending'],['Ongoing','Ongoing']] as [$val,$label])
                        <button @click="activeTab = '{{ $val }}'"
                                :class="activeTab === '{{ $val }}'
                                    ? 'border-blue-600 text-blue-700 bg-blue-50'
                                    : 'border-transparent text-gray-600 hover:text-gray-900'"
                                class="px-4 py-2 text-sm font-medium border-b-2 transition rounded-t-lg">
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>

                    {{-- TABLE --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-left border-b">
                                <tr>
                                    <th class="p-3 font-semibold">Ticket ID</th>
                                    <th class="p-3 font-semibold">Loading Site</th>
                                    <th class="p-3 font-semibold">Dumping Site</th>
                                    <th class="p-3 font-semibold">Lorry</th>
                                    <th class="p-3 font-semibold">Status</th>
                                    <th class="p-3 text-right font-semibold">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($driver->tickets ?? [] as $ticket)
                                <tr class="border-t hover:bg-gray-50"
                                    x-show="activeTab === 'all' || activeTab === '{{ $ticket->status }}'">
                                    <td class="p-3 font-medium">{{ $ticket->ticket_number }}</td>
                                    <td class="p-3">{{ $ticket->loadingSite->name ?? '-' }}</td>
                                    <td class="p-3">{{ $ticket->dumpingSite->name ?? '-' }}</td>
                                    <td class="p-3 font-mono text-xs">{{ $ticket->lorry->plate ?? '-' }}</td>
                                    <td class="p-3"><x-badge :status="$ticket->status" /></td>
                                    <td class="p-3 text-right">
                                        <x-btn href="{{ route('tickets.show', $ticket) }}" type="outline" size="sm" icon="eye" />
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-gray-400 italic text-sm">
                                        No tickets assigned.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        {{-- EDIT OFFCANVAS --}}
        <x-offcanvas id="editOpen" title="Edit Driver" saveText="Save Driver">
            <x-slot name="body">
                <form id="editDriverForm" method="POST"
                      action="{{ route('drivers.update', $driver) }}" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-sm font-medium mb-1">Full Name</label>
                        <input type="text" name="name" value="{{ $driver->name }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Employee ID</label>
                        <input type="text" name="employee_id" value="{{ $driver->employee_id }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">License Number</label>
                        <input type="text" name="license_number" value="{{ $driver->license_number }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">License Expiry</label>
                        <input type="date" name="license_expiry" value="{{ $driver->license_expiry?->format('Y-m-d') }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Certification</label>
                        <input type="text" name="certification" value="{{ $driver->certification }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ $driver->phone }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select name="status"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="Active" @selected($driver->status === 'Active')>Active</option>
                            <option value="Inactive" @selected($driver->status === 'Inactive')>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Notes</label>
                        <textarea name="notes" rows="3"
                                  class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ $driver->notes }}</textarea>
                    </div>
                </form>
            </x-slot>
            <x-slot name="footer">
                <x-btn type="secondary" @click="editOpen = false">Cancel</x-btn>
                <x-btn type="primary" @click="document.getElementById('editDriverForm').submit()">Save Driver</x-btn>
            </x-slot>
        </x-offcanvas>

    </div>

</x-layouts.app>
