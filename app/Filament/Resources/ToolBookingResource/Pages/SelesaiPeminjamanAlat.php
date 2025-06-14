<?php

namespace App\Filament\Resources\ToolBookingResource\Pages;

use App\Filament\Resources\ToolBookingResource;
use Filament\Resources\Pages\Page;
use App\Models\ToolBooking;
use App\Models\Tool;
use Filament\Forms;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;


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
            'data.jumlah_dikembalikan' => $this->booking->jumlah_dikembalikan,
            'data.bukti_selesai' => $this->booking->bukti_selesai,
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
                ->label('Bukti selesai')
                ->disk('public')
                ->directory('bukti-alat') // atau 'bukti-lab'
                ->required()
                ->image()
                ->preserveFilenames()
                ->getUploadedFileNameForStorageUsing(fn($file) => $file->hashName())
                ->maxFiles(1)
                ->multiple(false)
                ->columnSpanFull()
                ->statePath('data.bukti_selesai')
            // ->dehydrateStateUsing(fn($state) => is_array($state) ? ($state[0] ?? null) : $state)
        ];
    }

    public function submit()
    {
        Log::info('Data sebelum update:', [
            'jumlah_dikembalikan' => $this->data['jumlah_dikembalikan'] ?? null,
            'bukti_selesai' => $this->data['bukti_selesai'] ?? null,
        ]);

        // Ambil hanya path string dari hasil upload (bukan TemporaryUploadedFile)
        $bukti = [];
        if (is_array($this->data['bukti_selesai'])) {
            foreach ($this->data['bukti_selesai'] as $item) {
                if (is_string($item)) {
                    $bukti[] = $item;
                } elseif (is_object($item) && method_exists($item, 'getFilename')) {
                    $bukti[] = $item->getFilename();
                }
            }
        } elseif (is_string($this->data['bukti_selesai'])) {
            $bukti[] = $this->data['bukti_selesai'];
        }

        Log::info('Bukti selesai yang akan disimpan:', $bukti);

        $this->booking->update([
            'jumlah_dikembalikan' => $this->data['jumlah_dikembalikan'],
            'bukti_selesai' => $bukti,
            // 'status' => 'completed',
        ]);

        Log::info('Booking updated', [
            'booking_id' => $this->booking->id,
            'bukti_selesai' => $this->booking->bukti_selesai,
        ]);
        // Update stok alat
        $tool = $this->booking->tool;
        $tool->available_quantity += (int) $this->data['jumlah_dikembalikan'];
        $tool->save();

        session()->flash('success', 'Peminjaman alat berhasil diselesaikan.');
        return redirect()->route('filament.super-admin.resources.tool-bookings.index');
    }
}
