<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippingOriginResource\Pages;
use App\Filament\Resources\ShippingOriginResource\RelationManagers;
use App\Models\ShippingOrigin;
use App\Services\RajaOngkirService;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShippingOriginResource extends Resource
{
    protected static ?string $model = ShippingOrigin::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = "Shipping Warehouse";

    protected static function provinceOptions(RajaOngkirService $service): array
    {
        return collect($service->provinces())
            ->pluck('name', 'id')
            // ->map(fn ($label) => (string) $label) // guard
            ->toArray();
    }

    protected static function cityOptions(Get $get,RajaOngkirService $service):array
    {
        $provinceId = $get('province_id');

        if (!$provinceId) {
            return [];
        }

        $cities = $service->cities($provinceId);

        if(empty($cities)){
            return ['error' => "Failed to load cities. Please try again later."];
        }

        return collect($cities)
                ->pluck('name', 'id')
                ->toArray();
    }
    protected static function districtOptions(Get $get,RajaOngkirService $service):array
    {
        $cityId = $get('city_id');

        if (!$cityId) {
            return [];
        }

        $districts = $service->districts($cityId);

        if(empty($districts)){
            return ['error' => "Failed to load districts. Please try again later."];
        }

        return collect($districts)
                ->pluck('name', 'id')
                ->toArray();
    }
    protected static function subDistrictOptions(Get $get,RajaOngkirService $service):array
    {
        $districtId = $get('district_id');

        if (!$districtId) {
            return [];
        }

        $districts = $service->subDistricts($districtId);

        if(empty($districts)){
            return ['error' => "Failed to load sub-districts. Please try again later."];
        }

        return collect($districts)
                ->mapWithKeys(fn($item) => [
                    $item['id'] => $item['name'] . ' ('.($item['zip_code'] ?? 'N/A'). ')'
                ])
                // ->pluck('name', 'id')
                ->toArray();
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                            ->required()
                            ->label("Nama Gudang")
                            ->maxLength(255)
                            ->placeholder('Masukkan Nama Gudang'),
                Textarea::make('address')
                            ->required()
                            ->label("Alamat Gudang")
                            ->placeholder('Masukkan Alamat Gudang: Jalan / block, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi ')
                            ->maxLength(255),
                
                //PROVINSI
                Select::make('province_id')
                        ->label('Provinsi')
                        ->options(fn (RajaOngkirService $service) =>
                            static::provinceOptions($service)
                        )
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set, RajaOngkirService $service) {
                            if (!$state) {
                                $set('province_name', null);
                                $set('city_id', null);
                                $set('city_name', null);
                                $set('district_id', null);
                                $set('district_name', null);
                                $set('subdistrict_id', null);
                                $set('subdistrict_name', null);
                                $set('postal_code', null);
                                return;
                            }

                            $options = static::provinceOptions($service);

                            $set('province_name', $options[$state] ?? null);

                            // reset cascade
                            $set('city_id', null);
                            $set('city_name', null);
                            $set('district_id', null);
                            $set('district_name', null);
                            $set('subdistrict_id', null);
                            $set('subdistrict_name', null);
                        })
                        ->required(),
                Hidden::make('province_name'),

                // KOTA
                Select::make('city_id')
                        ->label('Kabupaten/Kota')
                        ->placeholder('Pilih Kabupaten/Kota')
                        ->searchable()
                        ->preload()
                        ->searchDebounce(300)
                        ->options(fn (Get $get, RajaOngkirService $service) =>
                            static::cityOptions($get, $service)
                        )
                        ->live()
                        ->afterStateUpdated(function ($state,Get $get, Set $set, RajaOngkirService $service) {
                            if (!$state) {
                                $set('city_name', null);
                                $set('district_id', null);
                                $set('district_name', null);
                                $set('subdistrict_id', null);
                                $set('subdistrict_name', null);
                                $set('postal_code', null);
                                return;
                            }

                            $options = static::cityOptions($get, $service);

                            $set('city_name', $options[$state] ?? null);
                            // Reset field di bawah kota
                            $set('district_id', null);
                            $set('district_name', null);
                            $set('subdistrict_id', null);
                            $set('subdistrict_name', null);
                            $set('postal_code', null);
                        })
                        ->loadingMessage("Loading Cities...")
                        ->required(),
                Hidden::make('city_name'),

                // kecamatan/ DISTRICT
                Select::make('district_id')
                        ->label('Kecamatan')
                        ->placeholder('Pilih Kecamatan')
                        ->searchable()
                        ->preload()
                        ->searchDebounce(300)
                        ->options(fn (Get $get, RajaOngkirService $service) =>
                            static::districtOptions($get, $service)
                        )
                        ->live()
                        ->afterStateUpdated(function ($state,Get $get, Set $set, RajaOngkirService $service) {
                            if (!$state) {
                                $set('district_name', null);
                                $set('subdistrict_id', null);
                                $set('subdistrict_name', null);
                                $set('postal_code', null);
                                return;
                            }

                            $options = static::districtOptions($get, $service);

                            $set('district_name', $options[$state] ?? null);
                            // Reset field di bawah kota
                            $set('subdistrict_id', null);
                            $set('subdistrict_name', null);
                            $set('postal_code', null);
                        })
                        ->loadingMessage("Loading Districts...")
                        ->required(),
                Hidden::make('district_name'),

                // kelurahan/ SUBDISTRICT
                Select::make('subdistrict_id')
                        ->label('Kelurahan/Desa')
                        ->placeholder('Pilih Kelurahan/Desa')
                        ->searchable()
                        ->preload()
                        ->searchDebounce(300)
                        ->options(fn (Get $get, RajaOngkirService $service) =>
                            static::subDistrictOptions($get, $service)
                        )
                        ->live()
                        ->afterStateUpdated(function ($state,Get $get, Set $set, RajaOngkirService $service) {
                            if (!$state) {
                                $set('subdistrict_name', null);
                                $set('postal_code', null);
                                return;
                            }

                            // $options = static::subDistrictOptions($get, $service);

                            $districtId = $get('district_id');
                            $subdistricts = $service->subDistricts((int) $districtId);
                            $selected = collect($subdistricts)->first(fn($item) => $item['id'] == $state);

                            $set('subdistrict_name', $selected['name'] ?? null);
                            // Reset field di bawah kota
                            $set('postal_code', $selected['zip_code'] ?? null);
                        })
                        ->loadingMessage("Loading Sub Districts...")
                        ->required(),
                Hidden::make('subdistrict_name'),

                // Kode Pos
                TextInput::make('postal_code')
                        ->label('Kode Pos')
                        ->readOnly(),

                Fieldset::make('Setting Gudang Pengiriman')
                        ->schema([
                            Toggle::make('is_active')
                                    ->default(true)
                                    ->label('Status Gudang')
                                    ->inline(false)
                                    ->helperText('Nonaktifkan jika gudang sedang tutup atau tidak beroperasi.'),
                            Toggle::make('is_default')
                                    ->default(false)
                                    ->label('Gudang Utama')
                                    ->helperText('Gudang ini akan digunakan sebagai lokasi pengiriman default untuk perhitungan ongkir.')
                                    ->live()
                                    ->inline(false),
                        ])
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                        ->label('Gudang Pengiriman')
                        ->searchable(),
                TextColumn::make('province_name')
                        ->label('Provinsi')
                        ->searchable(),
                TextColumn::make('city_name')
                        ->label('Kabupaten/Kota')
                        ->searchable(),
                TextColumn::make('district_name')
                        ->label('Kecamatan')
                        ->searchable(),
                TextColumn::make('subdistrict_name')
                        ->label('Kelurahan/Desa')
                        ->searchable(),
                TextColumn::make('postal_code')
                        ->label('Kode Pos')
                        ->searchable(),
                IconColumn::make('is_active')
                        ->label('Status Gudang')
                        ->boolean(),
                IconColumn::make('is_default')
                        ->label('Gudang Utama')
                        ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShippingOrigins::route('/'),
            'create' => Pages\CreateShippingOrigin::route('/create'),
            'edit' => Pages\EditShippingOrigin::route('/{record}/edit'),
        ];
    }


    
}