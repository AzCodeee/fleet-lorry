{{--
    Data Table Component
    Props:
      $title      — card heading
      $emptyText  — shown when $empty = true (or slot is empty)

    Slots:
      $actions    — buttons for the top-right of the card header
      $head       — <tr> with <th> columns
      $slot       — <tr> rows (tbody content)

    Usage:
      <x-data-table title="Drivers">
          <x-slot name="actions">
              <x-btn type="primary" icon="plus" @click="open = true">Add Driver</x-btn>
          </x-slot>
          <x-slot name="head">
              <th class="p-3">Name</th>
              <th class="p-3">Status</th>
          </x-slot>
          @foreach($drivers as $driver)
          <tr class="border-t hover:bg-gray-50">
              <td class="p-3">{{ $driver->name }}</td>
              <td class="p-3"><x-badge :status="$driver->status" /></td>
          </tr>
          @endforeach
      </x-data-table>
--}}

@props([
    'title'     => '',
    'emptyText' => 'No records found.',
])

<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="p-4 border-b flex justify-end">
        <input type="text" placeholder="Search region..." class="border rounded-lg px-3 py-2 w-64">
    </div>
    {{-- CARD HEADER --}}
    @if($title || isset($actions))
    <div class="p-4 border-b flex flex-wrap items-center justify-between gap-3">
        @if($title)
            <h3 class="font-semibold text-gray-800">{{ $title }}</h3>
        @endif
        @if(isset($actions))
            <div class="flex items-center gap-2 flex-wrap">
                {{ $actions }}
            </div>
        @endif
    </div>
    @endif

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">

            @if(isset($head))
            <thead class="bg-gray-50 text-left border-b border-gray-200">
                <tr>
                    {{ $head }}
                </tr>
            </thead>
            @endif

            <tbody>
                {{ $slot }}
            </tbody>

        </table>
    </div>

    {{-- PAGINATION (passed via named slot) --}}
    @if(isset($pagination))
    <div class="p-4 border-t">
        {{ $pagination }}
    </div>
    @endif

</div>
