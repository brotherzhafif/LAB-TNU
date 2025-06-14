<?php

namespace App\Filament\Resources\LabBookingResource\Pages;

use App\Filament\Resources\LabBookingResource;
use App\Models\LabBooking;
use Filament\Forms;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Storage;
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
            'data.bukti_selesai' => null,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\FileUpload::make('bukti_selesai')
                ->label('Upload Bukti Selesai')
                ->required()
                ->image()
                ->maxSize(2048)
                ->disk('public')
                ->directory('bukti-lab')
                ->statePath('data.bukti_selesai')
                ->maxFiles(1)
                ->multiple(false)
                ->columnSpanFull(),
        ];
    }

    public function submit()
    {
        $uploaded = $this->data['bukti_selesai'] ?? null;
        $path = null;

        // Handle upload manual
        if ($uploaded && is_object($uploaded) && method_exists($uploaded, 'store')) {
            $path = $uploaded->store('bukti-lab', 'public');
        } elseif (is_string($uploaded)) {
            $path = $uploaded;
        }

        $this->booking->update([
            'bukti_selesai' => $path,
            // 'status' => 'completed',
        ]);

        session()->flash('success', 'Peminjaman berhasil ditandai selesai.');
        return redirect()->route('filament.super-admin.resources.lab-bookings.index');
    }
}