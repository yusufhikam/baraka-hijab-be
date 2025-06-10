<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\SubCategory;
use Filament\Actions\ForceDeleteAction;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('sub_category_id')
                    ->relationship('subCategory', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->hint(function ($state) {
                        $subCategory = SubCategory::with('category')->find($state);
                        return $subCategory?->category?->name ? 'Category: ' . $subCategory->category->name : null;
                    }),
                Select::make('is_ready')
                    ->options([
                        true => 'Available',
                        false => 'Not Available'
                    ])
                    ->required()
                    ->label('Ketersediaan'),
                TextInput::make('price')
                    ->numeric()
                    ->prefix('IDR')
                    ->required(),
                FileUpload::make('thumbnail')
                    ->disk('public')
                    ->directory('thumbnails')
                    ->visibility('public')
                    ->image()
                    ->required(),
                Repeater::make('photos')
                    ->relationship('photos')
                    ->schema([
                        FileUpload::make('photo')
                            ->disk('public')
                            ->directory('products')
                            ->visibility('public')
                            ->image()
                            ->required(),
                    ]),
                Textarea::make('description')
                    ->rows(10)
                    ->cols(20)
                    ->required(),
                Repeater::make('productVariants')
                    ->relationship('productVariants')
                    ->schema([
                        TextInput::make('stock')
                            ->numeric()
                            ->required(),
                        TextInput::make('size')
                            ->required(),
                        ColorPicker::make('color')
                            ->required(),
                        TextInput::make('weight')
                            ->numeric()
                            ->prefix('grams')
                            ->required()
                            ->placeholder('e.g, 1000 for 1kg'),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('subCategory.category.name'),
                TextColumn::make('subCategory.name')
                    ->label('Sub Category'),
                ImageColumn::make('thumbnail')
                    ->disk('public'),
                TextColumn::make('price')
                    ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.'))
                    ->label('Price (IDR)'),
                ColorColumn::make('productVariants.color')
                    ->label('Color'),
                TextColumn::make('is_ready')
                    ->icon(fn(bool $state) => $state ? 'heroicon-o-check-badge' : 'heroicon-o-x-circle')
                    ->badge()
                    ->color(fn(bool $state) => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn($state) => $state ? 'Available' : 'Not Available')
                    ->label('Availability'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make()
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}