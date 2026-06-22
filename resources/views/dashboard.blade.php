<x-layouts.app title="Dashboard" pageTitle="Fleet Management System">

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <x-stat-card
            title="Total Revenue"
            :value="'RM ' . number_format($stats['total_revenue'] ?? 0, 2)"
            icon="dollar-sign"
            color="green" />

        <x-stat-card
            title="Idling Lorries"
            :value="$stats['idle_lorries'] ?? 0"
            icon="truck"
            color="red" />

        <x-stat-card
            title="Active Projects"
            :value="$stats['active_projects'] ?? 0"
            icon="folder"
            color="blue" />
    </div>

    {{-- CHART + FLEET STATUS --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

        {{-- Revenue Chart --}}
        <div class="bg-white rounded-xl shadow p-4 lg:col-span-2">
            <h3 class="font-semibold mb-4">Revenue Overview</h3>
            <canvas id="revenueChart" height="120"></canvas>
        </div>

        {{-- Fleet Status --}}
        <div class="bg-white rounded-xl shadow p-4">
            <h3 class="font-semibold mb-4">Fleet Status</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Running</span>
                    <span class="font-semibold text-green-600">{{ $fleetStatus['running'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Idle</span>
                    <span class="font-semibold text-yellow-600">{{ $fleetStatus['idle'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">In Maintenance</span>
                    <span class="font-semibold text-red-600">{{ $fleetStatus['maintenance'] ?? 0 }}</span>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Active Tickets</span>
                    <span class="font-semibold">{{ $stats['active_tickets'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Active Drivers</span>
                    <span class="font-semibold">{{ $stats['active_drivers'] ?? 0 }}</span>
                </div>
            </div>
        </div>

    </div>

    {{-- RECENT TICKETS --}}
    <x-data-table title="Recent Tickets">
        <x-slot name="actions">
            <x-btn href="{{ route('tickets.index') }}" type="outline" icon="arrow-right" size="sm">
                View All
            </x-btn>
        </x-slot>
        <x-slot name="head">
            <th class="p-3 font-semibold text-gray-700">Ticket ID</th>
            <th class="p-3 font-semibold text-gray-700">Project</th>
            <th class="p-3 font-semibold text-gray-700">Driver</th>
            <th class="p-3 font-semibold text-gray-700">Lorry</th>
            <th class="p-3 font-semibold text-gray-700">Status</th>
            <th class="p-3 font-semibold text-gray-700">Priority</th>
            <th class="p-3 text-right font-semibold text-gray-700">Action</th>
        </x-slot>

        @forelse($recentTickets ?? [] as $ticket)
        <tr class="border-t hover:bg-gray-50">
            <td class="p-3 font-medium text-gray-900">{{ $ticket->ticket_number }}</td>
            <td class="p-3">{{ $ticket->project->name ?? '-' }}</td>
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
            <td colspan="7" class="p-8 text-center text-gray-400 italic text-sm">No tickets yet.</td>
        </tr>
        @endforelse
    </x-data-table>

    @push('scripts')
    <script>
    const revenueData = @json($revenueChart ?? ['labels' => ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'], 'data' => [0,0,0,0,0,0,0]]);

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: revenueData.labels,
            datasets: [{
                label: 'Revenue (RM)',
                data: revenueData.data,
                borderColor: '#16a34a',
                backgroundColor: 'rgba(22,163,74,0.08)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { grid: { color: '#eee' } }
            }
        }
    });
    </script>
    @endpush

</x-layouts.app>
