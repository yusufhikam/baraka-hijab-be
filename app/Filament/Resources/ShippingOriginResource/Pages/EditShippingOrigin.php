<?php

namespace App\Filament\Resources\ShippingOriginResource\Pages;

use App\Filament\Resources\ShippingOriginResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShippingOrigin extends EditRecord
{
    protected static string $resource = ShippingOriginResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
