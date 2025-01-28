<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\EarlybirdResource\Pages;

use App\Filament\Admin\Resources\EarlybirdResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEarlybird extends CreateRecord
{
    protected static string $resource = EarlybirdResource::class;
}
