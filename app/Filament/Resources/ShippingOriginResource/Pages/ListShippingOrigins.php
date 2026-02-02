<?php

namespace App\Filament\Resources\ShippingOriginResource\Pages;

use App\Filament\Resources\ShippingOriginResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShippingOrigins extends ListRecords
{
    protected static string $resource = ShippingOriginResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
