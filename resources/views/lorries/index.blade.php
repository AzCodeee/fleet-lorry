<x-layouts.app title="Lorries" pageTitle="Fleet Management System">

    <div x-data="{ open: false }">

        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Lorries</h2>
                <p class="text-sm text-gray-500 mt-1">Manage fleet lorry vehicles</p>
            </div>
            <x-btn type="primary" icon="plus" @click="open = true">Add Lorry</x-btn>
        </div>

        <x-data-table>
            <x-slot name="head">
                <th class="p-3 font-semibold text-gray-700">Plate</th>
                <th class="p-3 font-semibold text-gray-700">VIN</th>
                <th class="p-3 font-semibold text-gray-700">Road Tax</th>
                <th class="p-3 font-semibold text-gray-700">Bucket Size</th>
                <th class="p-3 font-semibold text-gray-700">Utilization</th>
                <th class="p-3 font-semibold text-gray-700">Status</th>
                <th class="p-3 text-right font-semibold text-gray-700">Action</th>
            </x-slot>

            @forelse($lorries ?? [] as $lorry)
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3 font-mono font-bold text-gray-900">{{ $lorry->plate }}</td>
                <td class="p-3 font-mono text-xs text-gray-600">{{ $lorry->vin }}</td>
                <td class="p-3"><x-badge :status="$lorry->road_tax_status" /></td>
                <td class="p-3">{{ $lorry->bucket_size }}</td>
                <td class="p-3">
                    <div class="flex items-center gap-2">
                        <div class="flex-1 bg-gray-200 rounded-full h-2 min-w-[80px]">
                            <div class="h-2 rounded-full
                                {{ ($lorry->utilization ?? 0) >= 80 ? 'bg-green-500' : (($lorry->utilization ?? 0) >= 50 ? 'bg-yellow-500' : 'bg-red-400') }}"
                                 style="width: {{ min($lorry->utilization ?? 0, 100) }}%">
                            </div>
                        </div>
                        <span class="text-xs text-gray-600">{{ $lorry->utilization ?? 0 }}%</span>
                    </div>
                </td>
                <td class="p-3"><x-badge :status="$lorry->status" /></td>
                <td class="p-3 text-right">
                    <x-btn href="{{ route('lorries.show', $lorry) }}" type="outline" size="sm" icon="eye" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="p-8 text-center text-gray-400 italic text-sm">
                    No lorries found. Click "Add Lorry" to register one.
                </td>
            </tr>
            @endforelse
        </x-data-table>

        @if(isset($lorries) && $lorries->hasPages())
        <div class="mt-4">{{ $lorries->links() }}</div>
        @endif

        {{-- ADD LORRY OFFCANVAS --}}
        <x-offcanvas id="open" title="Add Lorry" saveText="Add Lorry">
            <x-slot name="body">
                <form id="addLorryForm" method="POST" action="{{ route('lorries.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-1">Car Plate <span class="text-red-500">*</span></label>
                        <input type="text" name="plate" required
                               placeholder="e.g. WXY 1234"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">VIN Number</label>
                        <input type="text" name="vin"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Road Tax Status</label>
                        <select name="road_tax_status"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="Valid">Valid</option>
                            <option value="Expired">Expired</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Road Tax Expiry</label>
                        <input type="date" name="road_tax_expiry"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Bucket Size</label>
                        <input type="text" name="bucket_size"
                               placeholder="e.g. 10 Ton"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Assigned Driver</label>
                        <select name="driver_id"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">— None —</option>
                            @foreach($drivers ?? [] as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Utilization %</label>
                        <input type="number" name="utilization" min="0" max="100" value="0"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select name="status"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="Active">Active</option>
                            <option value="In Repair">In Repair</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </form>
            </x-slot>
            <x-slot name="footer">
                <x-btn type="secondary" @click="open = false">Cancel</x-btn>
                <x-btn type="primary" @click="document.getElementById('addLorryForm').submit()">Add Lorry</x-btn>
            </x-slot>
        </x-offcanvas>

    </div>

</x-layouts.app>
