<?php

namespace App\Filament\Resources\ToolBookingResource\Pages;

use App\Filament\Resources\ToolBookingResource;
use Filament\Resources\Pages\Page;
use App\Models\ToolBooking;
use App\Models\Tool;
use Filament\Forms;
use Livewire\WithFileUploads;


class SelesaiPeminjamanAlat extends Page
{
    use Forms\Concerns\InteractsWithForms;
    use WithFileUploads;

    protected static string $resource = ToolBookingResource::class;
    protected static string $view = 'filament.resources.tool-booking-resource.pages.selesai-peminjaman-alat';

    public ToolBooking $booking;
    public ?array $data = [];

    public function mount($record): void
    {
        $this->booking = ToolBooking::findOrFail($record);
        abort_unless(auth()->id() === $this->booking->user_id, 403);

        $this->form->fill([
            'jumlah_dikembalikan' => $this->booking->jumlah_dikembalikan,
            'bukti_selesai' => $this->booking->bukti_selesai,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('jumlah_dikembalikan')
                ->label('Jumlah Alat Dikembalikan')
                ->numeric()
                ->required()
                ->minValue(0)
                ->maxValue($this->booking->jumlah)
                ->statePath('data.jumlah_dikembalikan'),

            Forms\Components\FileUpload::make('bukti_selesai')
                ->label('Upload Bukti Selesai')
                ->directory('bukti-alat')
                ->required()
                ->statePath('data.bukti_selesai'),
        ];
    }

    public function submit()
    {
        $this->booking->update([
            'jumlah_dikembalikan' => $this->data['jumlah_dikembalikan'],
            'bukti_selesai' => $this->data['bukti_selesai'] ?? null,
            'selesai' => true,
        ]);

        // Update stok alat
        $tool = $this->booking->tool;
        $tool->available_quantity += (int) $this->data['jumlah_dikembalikan'];
        $tool->save();

        session()->flash('success', 'Peminjaman alat berhasil diselesaikan.');
        return redirect()->route('filament.super-admin.resources.tool-bookings.index');
    }
}
