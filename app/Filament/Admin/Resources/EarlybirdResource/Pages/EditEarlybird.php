<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\EarlybirdResource\Pages;

use App\Filament\Admin\Resources\EarlybirdResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditEarlybird extends EditRecord
{
    protected static string $resource = EarlybirdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);
        $record->pembayaran()->update([
            'ukuran_jersey' => $data['ukuran_jersey'],
            'kategori_lomba' => $data['kategori_lomba'],
        ]);
        return $record;
    }
}
