<x-filament-widgets::widget>
    <x-filament::section>
        {{-- Filter Periode --}}
        <div class="flex flex-col md:flex-row gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Periode</label>
                <select wire:model="filterPeriode" class="filament-input rounded-lg">
                    <option value="hari">Hari</option>
                    <option value="bulan">Bulan</option>
                    <option value="tahun">Tahun</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Tanggal</label>
                <input type="date" wire:model="filterTanggal" class="filament-input rounded-lg" />
            </div>
        </div>
        {{-- Widget content --}}
        {{-- Statistik akan otomatis berubah sesuai filter --}}
    </x-filament::section>
</x-filament-widgets::widget>