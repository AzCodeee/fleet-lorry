<x-layouts.app title="Drivers" pageTitle="Fleet Management System">

    <div x-data="{ open: false }">

        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Drivers</h2>
                <p class="text-sm text-gray-500 mt-1">Manage registered fleet drivers</p>
            </div>
            <x-btn type="primary" icon="plus" @click="open = true">Add Driver</x-btn>
        </div>

        <x-data-table>
            <x-slot name="head">
                <th class="p-3 font-semibold text-gray-700">Name</th>
                <th class="p-3 font-semibold text-gray-700">Employee ID</th>
                <th class="p-3 font-semibold text-gray-700">License No.</th>
                <th class="p-3 font-semibold text-gray-700">License Expiry</th>
                <th class="p-3 font-semibold text-gray-700">Assigned Lorry</th>
                <th class="p-3 font-semibold text-gray-700">Status</th>
                <th class="p-3 text-right font-semibold text-gray-700">Action</th>
            </x-slot>

            @forelse($drivers ?? [] as $driver)
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3 font-medium text-gray-900">{{ $driver->name }}</td>
                <td class="p-3 font-mono text-xs">{{ $driver->employee_id }}</td>
                <td class="p-3 font-mono text-xs">{{ $driver->license_number }}</td>
                <td class="p-3">
                    @if($driver->license_expiry)
                        <span class="{{ $driver->license_expiry->isPast() ? 'text-red-600 font-medium' : '' }}">
                            {{ $driver->license_expiry->format('d M Y') }}
                        </span>
                        @if($driver->license_expiry->isPast())
                            <x-badge status="Expired" />
                        @endif
                    @else —
                    @endif
                </td>
                <td class="p-3 font-mono text-xs">{{ $driver->lorry->plate ?? '—' }}</td>
                <td class="p-3"><x-badge :status="$driver->status" /></td>
                <td class="p-3 text-right">
                    <x-btn href="{{ route('drivers.show', $driver) }}" type="outline" size="sm" icon="eye" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="p-8 text-center text-gray-400 italic text-sm">
                    No drivers found. Click "Add Driver" to register one.
                </td>
            </tr>
            @endforelse
        </x-data-table>

        @if(isset($drivers) && $drivers->hasPages())
        <div class="mt-4">{{ $drivers->links() }}</div>
        @endif

        {{-- ADD DRIVER OFFCANVAS --}}
        <x-offcanvas id="open" title="Add Driver" saveText="Add Driver">
            <x-slot name="body">
                <form id="addDriverForm" method="POST" action="{{ route('drivers.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-1">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Employee ID</label>
                        <input type="text" name="employee_id"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">License Number</label>
                        <input type="text" name="license_number"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">License Expiry</label>
                        <input type="date" name="license_expiry"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Certification</label>
                        <input type="text" name="certification"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Phone</label>
                        <input type="text" name="phone"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
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
                        <label class="block text-sm font-medium mb-1">Notes</label>
                        <textarea name="notes" rows="3"
                                  class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                    </div>
                </form>
            </x-slot>
            <x-slot name="footer">
                <x-btn type="secondary" @click="open = false">Cancel</x-btn>
                <x-btn type="primary" @click="document.getElementById('addDriverForm').submit()">Add Driver</x-btn>
            </x-slot>
        </x-offcanvas>

    </div>

</x-layouts.app>
