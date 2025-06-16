<x-filament-widgets::widget>
    <x-filament::section>
        {{-- Filter Section --}}
        @if (method_exists($this, 'getFormSchema') && count($this->getFormSchema()))
            <form wire:submit.prevent="filter" class="flex flex-wrap gap-2 mb-4">
                <div>
                    <x-filament::form wire:submit.prevent>
                        @foreach ($this->getFormSchema() as $field)
                            {!! $field->render() !!}
                        @endforeach
                    </x-filament::form>
                </div>
            </form>
        @endif
        {{-- Statistik akan otomatis berubah sesuai filter di atas --}}
        {{-- Widget content --}}
    </x-filament::section>
</x-filament-widgets::widget>