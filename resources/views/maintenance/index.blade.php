<x-layouts.app title="Maintenance" pageTitle="Fleet Management System">

    <div x-data="{ open: false }">

        {{-- STAT CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <x-stat-card
                title="Active Lorry"
                :value="$stats['active'] ?? 0"
                icon="truck"
                color="green-solid"
                :clickable="true" />

            <x-stat-card
                title="In Repair"
                :value="$stats['in_repair'] ?? 0"
                icon="wrench"
                color="yellow-solid"
                :clickable="true" />

            <x-stat-card
                title="Inactive"
                :value="$stats['inactive'] ?? 0"
                icon="ban"
                color="red-solid"
                :clickable="true" />
        </div>

        {{-- PAGE HEADER --}}
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Maintenance Records</h2>
                <p class="text-sm text-gray-500 mt-1">Track all lorry maintenance and repairs</p>
            </div>
            <x-btn type="primary" icon="plus" @click="open = true">Log Maintenance</x-btn>
        </div>

        {{-- TABLE --}}
        <x-data-table>
            <x-slot name="head">
                <th class="p-3 font-semibold text-gray-700">Lorry</th>
                <th class="p-3 font-semibold text-gray-700">Issue</th>
                <th class="p-3 font-semibold text-gray-700">Type</th>
                <th class="p-3 font-semibold text-gray-700">Date</th>
                <th class="p-3 font-semibold text-gray-700">Cost (RM)</th>
                <th class="p-3 font-semibold text-gray-700">Status</th>
                <th class="p-3 text-right font-semibold text-gray-700">Action</th>
            </x-slot>

            @forelse($records ?? [] as $record)
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3 font-mono font-medium">{{ $record->lorry->plate ?? '-' }}</td>
                <td class="p-3">{{ $record->issue }}</td>
                <td class="p-3">{{ $record->type }}</td>
                <td class="p-3">{{ $record->date?->format('d M Y') }}</td>
                <td class="p-3">{{ number_format($record->cost, 2) }}</td>
                <td class="p-3"><x-badge :status="$record->status" /></td>
                <td class="p-3 text-right">
                    <x-btn href="{{ route('maintenance.show', $record) }}" type="outline" size="sm" icon="eye" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="p-8 text-center text-gray-400 italic text-sm">
                    No maintenance records. Click "Log Maintenance" to add one.
                </td>
            </tr>
            @endforelse
        </x-data-table>

        @if(isset($records) && $records->hasPages())
        <div class="mt-4">{{ $records->links() }}</div>
        @endif

        {{-- LOG MAINTENANCE OFFCANVAS --}}
        <x-offcanvas id="open" title="Log Maintenance" saveText="Submit">
            <x-slot name="body">
                <form id="addMaintForm" method="POST" action="{{ route('maintenance.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-1">Lorry <span class="text-red-500">*</span></label>
                        <select name="lorry_id" required
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">— Select Lorry —</option>
                            @foreach($lorries ?? [] as $lorry)
                                <option value="{{ $lorry->id }}">{{ $lorry->plate }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Issue / Description <span class="text-red-500">*</span></label>
                        <input type="text" name="issue" required
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Type</label>
                        <select name="type"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="Preventive">Preventive</option>
                            <option value="Corrective">Corrective</option>
                            <option value="Emergency">Emergency</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Date</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Estimated Cost (RM)</label>
                        <input type="number" name="cost" step="0.01"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select name="status"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="Pending">Pending</option>
                            <option value="In Repair">In Repair</option>
                            <option value="Completed">Completed</option>
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
                <x-btn type="primary" @click="document.getElementById('addMaintForm').submit()">Submit</x-btn>
            </x-slot>
        </x-offcanvas>

    </div>

</x-layouts.app>
