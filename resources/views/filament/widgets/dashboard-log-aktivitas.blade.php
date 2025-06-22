<x-filament::widget>
    <x-filament::card>
        <h2 class="text-xl font-bold mb-4">Log Aktivitas Lab</h2>
        {{ $this->getLabTableInstance() }}
    </x-filament::card>

    <x-filament::card class="mt-6">
        <h2 class="text-xl font-bold mb-4">Log Aktivitas Alat</h2>
        {{ $this->getToolTableInstance() }}
    </x-filament::card>
</x-filament::widget>