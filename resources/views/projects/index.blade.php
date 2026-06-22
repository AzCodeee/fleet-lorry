<x-layouts.app title="Projects" pageTitle="Fleet Management System">

    <div x-data="{ open: false, editOpen: false, deleteId: null }">

        {{-- PAGE HEADER --}}
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Projects</h2>
                <p class="text-sm text-gray-500 mt-1">Manage all fleet projects</p>
            </div>
            <x-btn type="primary" icon="plus" @click="open = true">Add Project</x-btn>
        </div>

        {{-- TABLE --}}
        <x-data-table>
            <x-slot name="head">
                <th class="p-3 font-semibold text-gray-700">Project Name</th>
                <th class="p-3 font-semibold text-gray-700">Client</th>
                <th class="p-3 font-semibold text-gray-700">Region</th>
                <th class="p-3 font-semibold text-gray-700">Start Date</th>
                <th class="p-3 font-semibold text-gray-700">End Date</th>
                <th class="p-3 font-semibold text-gray-700">Status</th>
                <th class="p-3 text-right font-semibold text-gray-700">Action</th>
            </x-slot>

            @forelse($projects ?? [] as $project)
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3 font-medium text-gray-900">{{ $project->name }}</td>
                <td class="p-3">{{ $project->client }}</td>
                <td class="p-3">{{ $project->region->name ?? '-' }}</td>
                <td class="p-3">{{ $project->start_date?->format('d M Y') }}</td>
                <td class="p-3">{{ $project->end_date?->format('d M Y') }}</td>
                <td class="p-3"><x-badge :status="$project->status" /></td>
                <td class="p-3 text-right">
                    <x-btn href="{{ route('projects.show', $project) }}" type="outline" size="sm" icon="eye" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="p-8 text-center text-gray-400 italic text-sm">
                    No projects found. Click "Add Project" to create one.
                </td>
            </tr>
            @endforelse
        </x-data-table>

        {{-- PAGINATION --}}
        @if(isset($projects) && $projects->hasPages())
        <div class="mt-4">{{ $projects->links() }}</div>
        @endif

        {{-- ADD PROJECT OFFCANVAS --}}
        <x-offcanvas id="open" title="Add Project" saveText="Create Project">
            <x-slot name="body">
                <form id="addProjectForm" method="POST" action="{{ route('projects.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-1">Project Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Client</label>
                        <input type="text" name="client"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Region</label>
                        <select name="region_id" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">— Select Region —</option>
                            @foreach($regions ?? [] as $region)
                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Start Date</label>
                            <input type="date" name="start_date"
                                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">End Date</label>
                            <input type="date" name="end_date"
                                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select name="status" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="Active">Active</option>
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea name="description" rows="3"
                                  class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                    </div>
                </form>
            </x-slot>
            <x-slot name="footer">
                <x-btn type="secondary" @click="open = false">Cancel</x-btn>
                <x-btn type="primary" @click="document.getElementById('addProjectForm').submit()">Create Project</x-btn>
            </x-slot>
        </x-offcanvas>

    </div>

</x-layouts.app>
