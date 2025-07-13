<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use App\Models\SubCategory;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\ProductResource\Pages;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ViewColumn;

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
                            Select::make('is_ready')
                            ->options([
                                true => 'Available',
                                false => 'Not Available'
                            ])
                            ->required()
                            ->label('Ketersediaan'),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->searchable()
                ->label('Product Name'),
                ImageColumn::make('thumbnail')
                ->size(100),
                TextColumn::make('subCategory.category.name')
                ->label('Category'),
                TextColumn::make('subCategory.name')
                ->label('Sub Category'),
                TextColumn::make('price')
                ->label('Price (IDR)')
                ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                ViewColumn::make('productVariants')
                ->label('Product Variants')
                ->view('filament.adminPanel.product-variant')])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                    ViewAction::make(),
                ])
                ->button()
                ->color('info')
                ->label('Actions'),
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