{{--
    Offcanvas Component
    Props:
      $id       — Alpine variable name that controls open/close (e.g. "open", "editOpen")
      $title    — panel heading
      $saveText — text for the save/submit button (default: "Save")
      $saveType — 'primary' | 'dark' (default: 'primary')

    Usage:
      <x-offcanvas id="open" title="Add Driver" saveText="Add Driver">
          <x-slot name="body">
              ... form fields ...
          </x-slot>
      </x-offcanvas>

    The parent Alpine component must declare the variable, e.g.:
      x-data="{ open: false }"
    The trigger button should do: @click="open = true"
--}}

@props([
    'id'       => 'open',
    'title'    => 'Panel',
    'saveText' => 'Save',
    'saveType' => 'primary',
    'noFooter' => false,
])

<div x-show="{{ $id }}"
     x-transition
     class="fixed inset-0 flex justify-end bg-black/40 z-50">

    <div @click.away="{{ $id }} = false"
         class="w-full max-w-md bg-white h-screen shadow-xl flex flex-col">

        {{-- HEADER --}}
        <div class="flex justify-between items-center p-6 border-b shrink-0">
            <h2 class="text-lg font-semibold">{{ $title }}</h2>
            <button @click="{{ $id }} = false"
                    class="text-gray-500 hover:text-black transition">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        {{-- BODY --}}
        <div class="flex-1 overflow-y-auto p-6">
            {{ $body ?? $slot }}
        </div>

        {{-- FOOTER --}}
        @unless($noFooter)
        <div class="border-t p-6 flex justify-end gap-2 shrink-0">
            <x-btn type="secondary" @click="{{ $id }} = false">Cancel</x-btn>
            {{ $footer ?? '' }}
            @if(!isset($footer))
                <x-btn type="{{ $saveType }}" :submit="true">{{ $saveText }}</x-btn>
            @endif
        </div>
        @endunless

    </div>
</div>
