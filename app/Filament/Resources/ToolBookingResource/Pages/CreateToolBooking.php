<?php

namespace App\Filament\Resources\ToolBookingResource\Pages;

use App\Filament\Resources\ToolBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateToolBooking extends CreateRecord
{
    protected static string $resource = ToolBookingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->hasRole('pengguna')) {
            $data['user_id'] = auth()->id();
        }
        // Prefill tool_id if present in query string
        if (request()->has('tool_id')) {
            $data['tool_id'] = request()->get('tool_id');
        }
        return $data;
    }

    protected function getFormSchema(): array
    {
        $toolId = request()->get('tool_id');
        return [
            \Filament\Forms\Components\Select::make('tool_id')
                ->label('Alat')
                ->relationship('tool', 'name')
                ->required()
                ->default($toolId)
                ->disabled(fn() => filled($toolId)),
            // ...existing code for other fields...
        ];
    }

    public function afterCreate()
    {
        $tool = $this->record->tool;
        $tool->available_quantity -= $this->record->jumlah;
        $tool->save();
    }
}
