<?php

namespace App\Filament\Resources\LabBookingResource\Pages;

use App\Filament\Resources\LabBookingResource;
use App\Models\LabBooking;
use Filament\Forms;
use Filament\Resources\Pages\Page;
use Livewire\WithFileUploads;

class SelesaiPeminjamanLab extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;
    use WithFileUploads;

    protected static string $resource = LabBookingResource::class;
    protected static string $view = 'filament.resources.lab-booking-resource.pages.selesai-peminjaman-lab';

    public LabBooking $booking;
    public ?array $data = [];

    public function mount($record): void
    {
        $this->booking = LabBooking::findOrFail($record);

        abort_unless(auth()->id() === $this->booking->user_id, 403);

        $this->form->fill([
            'data.bukti_selesai' => $this->booking->bukti_selesai,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\FileUpload::make('bukti_selesai')
                ->label('Upload Bukti Selesai')
                ->directory('bukti-lab')
                ->required()
                ->statePath('data.bukti_selesai'),
        ];
    }

    public function submit()
    {
        $this->booking->update([
            'bukti_selesai' => $this->data['bukti_selesai'],
            'selesai' => true,
        ]);

        session()->flash('success', 'Peminjaman berhasil ditandai selesai.');
        return redirect()->route('filament.super-admin.resources.lab-bookings.index');
    }
}