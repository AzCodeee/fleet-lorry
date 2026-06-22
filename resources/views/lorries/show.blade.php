<x-layouts.app title="Lorry Detail" pageTitle="Fleet Management System">

    <div x-data="{ editOpen: false, sparePartOpen: false }">

        <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('lorries.index') }}" class="hover:text-green-600 transition">Lorries</a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <span class="text-gray-800 font-medium">{{ $lorry->plate }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LORRY INFO --}}
            <div class="lg:col-span-1 space-y-4">

                <div class="bg-white rounded-xl shadow p-6">

                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h2 class="text-2xl font-bold font-mono text-gray-900">{{ $lorry->plate }}</h2>
                            <p class="text-xs text-gray-500 mt-1">{{ $lorry->vin }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-badge :status="$lorry->status" />
                            <button @click="editOpen = true"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 hover:bg-white hover:shadow transition border border-gray-200">
                                <i data-lucide="pencil" class="w-4 h-4 text-gray-600"></i>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-500">Road Tax</span>
                            <x-badge :status="$lorry->road_tax_status" />
                        </div>
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-500">Road Tax Expiry</span>
                            <span class="font-medium">{{ $lorry->road_tax_expiry?->format('d M Y') ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-500">Bucket Size</span>
                            <span class="font-medium">{{ $lorry->bucket_size ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-500">Assigned Driver</span>
                            @if($lorry->driver)
                                <a href="{{ route('drivers.show', $lorry->driver) }}"
                                   class="font-medium text-green-600 hover:underline">{{ $lorry->driver->name }}</a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </div>
                    </div>

                    {{-- UTILIZATION --}}
                    <div class="mt-6">
                        <div class="flex justify-between mb-2 text-sm">
                            <span class="text-gray-500">Utilization Rate</span>
                            <span class="font-semibold">{{ $lorry->utilization ?? 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="h-3 rounded-full bg-blue-500"
                                 style="width: {{ min($lorry->utilization ?? 0, 100) }}%">
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            {{-- RIGHT: TABS --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- MAINTENANCE TABLE --}}
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <div class="p-4 border-b flex justify-between items-center">
                        <h3 class="font-semibold">Recent Maintenance</h3>
                        <x-btn href="{{ route('maintenance.index') }}" type="outline" size="sm">View All</x-btn>
                    </div>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-left border-b">
                            <tr>
                                <th class="p-3 font-semibold">Issue</th>
                                <th class="p-3 font-semibold">Date</th>
                                <th class="p-3 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lorry->maintenanceRecords ?? [] as $maint)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-3">{{ $maint->issue }}</td>
                                <td class="p-3">{{ $maint->date?->format('d M Y') }}</td>
                                <td class="p-3"><x-badge :status="$maint->status" /></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="p-6 text-center text-gray-400 italic text-sm">
                                    No maintenance records.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- SPARE PARTS TABLE --}}
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <div class="p-4 border-b flex justify-between items-center">
                        <h3 class="font-semibold">Spare Parts</h3>
                        <x-btn type="primary" icon="plus" size="sm" @click="sparePartOpen = true">Add Part</x-btn>
                    </div>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-left border-b">
                            <tr>
                                <th class="p-3 font-semibold">Part Name</th>
                                <th class="p-3 font-semibold">Installed Date</th>
                                <th class="p-3 font-semibold">Condition</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lorry->spareParts ?? [] as $part)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-3">{{ $part->name }}</td>
                                <td class="p-3">{{ $part->pivot->installed_date ?? '—' }}</td>
                                <td class="p-3"><x-badge :status="$part->pivot->condition ?? 'Good'" /></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="p-6 text-center text-gray-400 italic text-sm">
                                    No spare parts recorded.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        {{-- EDIT LORRY OFFCANVAS --}}
        <x-offcanvas id="editOpen" title="Edit Lorry" saveText="Save Lorry">
            <x-slot name="body">
                <form id="editLorryForm" method="POST"
                      action="{{ route('lorries.update', $lorry) }}" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-sm font-medium mb-1">Car Plate</label>
                        <input type="text" name="plate" value="{{ $lorry->plate }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">VIN Number</label>
                        <input type="text" name="vin" value="{{ $lorry->vin }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Road Tax Status</label>
                        <select name="road_tax_status"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="Valid" @selected($lorry->road_tax_status === 'Valid')>Valid</option>
                            <option value="Expired" @selected($lorry->road_tax_status === 'Expired')>Expired</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Bucket Size</label>
                        <input type="text" name="bucket_size" value="{{ $lorry->bucket_size }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Assigned Driver</label>
                        <select name="driver_id"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">— None —</option>
                            @foreach($drivers ?? [] as $driver)
                                <option value="{{ $driver->id }}" @selected($lorry->driver_id === $driver->id)>{{ $driver->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Utilization %</label>
                        <input type="number" name="utilization" min="0" max="100" value="{{ $lorry->utilization ?? 0 }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select name="status"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            @foreach(['Active','In Repair','Inactive'] as $s)
                                <option value="{{ $s }}" @selected($lorry->status === $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </x-slot>
            <x-slot name="footer">
                <x-btn type="secondary" @click="editOpen = false">Cancel</x-btn>
                <x-btn type="primary" @click="document.getElementById('editLorryForm').submit()">Save Lorry</x-btn>
            </x-slot>
        </x-offcanvas>

        {{-- ADD SPARE PART OFFCANVAS --}}
        <x-offcanvas id="sparePartOpen" title="Add Spare Part" saveText="Add Part">
            <x-slot name="body">
                <form id="addSparePartForm" method="POST"
                      action="{{ route('lorries.spare-parts.store', $lorry) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-1">Part Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Part Number</label>
                        <input type="text" name="part_number"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Installed Date</label>
                        <input type="date" name="installed_date" value="{{ date('Y-m-d') }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Condition</label>
                        <select name="condition"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="New">New</option>
                            <option value="Good">Good</option>
                            <option value="Worn">Worn</option>
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
                <x-btn type="secondary" @click="sparePartOpen = false">Cancel</x-btn>
                <x-btn type="primary" @click="document.getElementById('addSparePartForm').submit()">Add Part</x-btn>
            </x-slot>
        </x-offcanvas>

    </div>

</x-layouts.app>
