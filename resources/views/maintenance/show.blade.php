<x-layouts.app title="Maintenance Detail" pageTitle="Fleet Management System">

    <div x-data="{ editOpen: false }">

        <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('maintenance.index') }}" class="hover:text-green-600 transition">Maintenance</a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <span class="text-gray-800 font-medium">{{ $record->issue }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $record->issue }}</h2>
                            <p class="text-sm text-gray-500 mt-1">{{ $record->type }}</p>
                        </div>
                        <div class="flex gap-2">
                            <x-badge :status="$record->status" />
                            <x-btn type="outline" icon="pencil" size="sm" @click="editOpen = true">Edit</x-btn>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6 text-sm">
                        <div>
                            <p class="text-gray-500">Lorry</p>
                            <p class="font-semibold font-mono mt-1">
                                @if($record->lorry)
                                    <a href="{{ route('lorries.show', $record->lorry) }}"
                                       class="text-green-600 hover:underline">{{ $record->lorry->plate }}</a>
                                @else —
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500">Date</p>
                            <p class="font-semibold mt-1">{{ $record->date?->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Cost</p>
                            <p class="font-semibold mt-1">RM {{ number_format($record->cost, 2) }}</p>
                        </div>
                    </div>

                    @if($record->notes)
                    <div class="mt-6 pt-4 border-t text-sm">
                        <p class="text-gray-500 mb-1">Notes</p>
                        <p class="text-gray-700">{{ $record->notes }}</p>
                    </div>
                    @endif
                </div>

                {{-- SPARE PARTS --}}
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <div class="p-4 border-b font-semibold flex justify-between items-center">
                        <span>Spare Parts Used</span>
                    </div>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-left border-b">
                            <tr>
                                <th class="p-3">Part Name</th>
                                <th class="p-3">Quantity</th>
                                <th class="p-3">Unit Price (RM)</th>
                                <th class="p-3">Total (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($record->spareParts ?? [] as $part)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-3">{{ $part->name }}</td>
                                <td class="p-3">{{ $part->pivot->quantity }}</td>
                                <td class="p-3">{{ number_format($part->pivot->unit_price, 2) }}</td>
                                <td class="p-3 font-medium">{{ number_format($part->pivot->quantity * $part->pivot->unit_price, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-6 text-center text-gray-400 italic text-sm">
                                    No spare parts recorded.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- SIDEBAR --}}
            <div class="space-y-4">
                <div class="bg-white rounded-xl shadow p-4">
                    <h3 class="font-semibold mb-3 text-sm">Update Status</h3>
                    <form method="POST" action="{{ route('maintenance.update', $record) }}" class="space-y-3">
                        @csrf @method('PATCH')
                        <select name="status"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                            @foreach(['Pending','In Repair','Completed'] as $s)
                                <option value="{{ $s }}" @selected($record->status === $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                        <x-btn type="primary" :submit="true" class="w-full justify-center">Update</x-btn>
                    </form>
                </div>

                @if($record->lorry)
                <div class="bg-white rounded-xl shadow p-4 text-sm">
                    <h3 class="font-semibold mb-3">Lorry Info</h3>
                    <p class="font-mono font-bold text-gray-900">{{ $record->lorry->plate }}</p>
                    <p class="text-gray-500 mt-1">{{ $record->lorry->bucket_size }}</p>
                    <div class="mt-2"><x-badge :status="$record->lorry->status" /></div>
                    <div class="mt-3">
                        <x-btn href="{{ route('lorries.show', $record->lorry) }}" type="outline" size="sm" icon="eye">View Lorry</x-btn>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- EDIT OFFCANVAS --}}
        <x-offcanvas id="editOpen" title="Edit Maintenance" saveText="Save Changes">
            <x-slot name="body">
                <form id="editMaintForm" method="POST"
                      action="{{ route('maintenance.update', $record) }}" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-sm font-medium mb-1">Issue</label>
                        <input type="text" name="issue" value="{{ $record->issue }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Type</label>
                        <select name="type"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            @foreach(['Preventive','Corrective','Emergency'] as $t)
                                <option value="{{ $t }}" @selected($record->type === $t)>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Date</label>
                        <input type="date" name="date" value="{{ $record->date?->format('Y-m-d') }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Cost (RM)</label>
                        <input type="number" name="cost" value="{{ $record->cost }}" step="0.01"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select name="status"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            @foreach(['Pending','In Repair','Completed'] as $s)
                                <option value="{{ $s }}" @selected($record->status === $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Notes</label>
                        <textarea name="notes" rows="3"
                                  class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ $record->notes }}</textarea>
                    </div>
                </form>
            </x-slot>
            <x-slot name="footer">
                <x-btn type="secondary" @click="editOpen = false">Cancel</x-btn>
                <x-btn type="primary" @click="document.getElementById('editMaintForm').submit()">Save Changes</x-btn>
            </x-slot>
        </x-offcanvas>

    </div>

</x-layouts.app>
