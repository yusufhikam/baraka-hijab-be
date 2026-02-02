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
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\ProductResource\Pages;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\RichEditor;
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
                    ->optimize('webp', ['quality' => 80])
                    ->required(),
                RichEditor::make('description')
                            ->columnSpanFull()
                            ->disableToolbarButtons([
                                'attachFiles',
                            ]),
                Repeater::make('productVariants')
                    ->relationship('productVariants')
                    ->label('Product Variants')
                    ->itemLabel(function ($state){
                        return $state['name'] ? "Variant : " . ($state['name']) : null;
                    })
                    ->schema([
                        TextInput::make('name')
                                    ->required(),
                        ColorPicker::make('color')
                                            ->required(),
                        TextInput::make('weight')
                            ->label('Weight')
                            ->numeric()
                            ->prefix('grams')
                            ->required()
                            ->placeholder('e.g, 1000 for 1kg'),
                        Repeater::make('productVariantOptions')
                                ->relationship('productVariantOptions')
                                ->label("Variant Options")
                                ->addActionLabel("Add Variant Option")
                                ->itemLabel(function ($state){
                                    return $state['size'] ? "Option : " . ($state['size']) : null;
                                })
                                ->schema([
                                    Fieldset::make('Option')
                                            ->schema([
                                                TextInput::make('size')
                                                            ->required(),
                                                TextInput::make('stock')
                                                            ->numeric()
                                                            ->required(),
                                                Select::make('is_ready')
                                                        ->options([
                                                            true => 'Available',
                                                            false => 'Not Available'
                                                        ])
                                                        ->required(),
                                                
                                                ])
                                                ->columns(1)
                                ])
                                ->collapsible()
                                ->columnSpan(2)
                                ->defaultItems(1),
                        Repeater::make('photos')
                                ->relationship('photos')
                                ->schema([
                                    FileUpload::make('photo')
                                        ->disk('public')
                                        ->directory('products')
                                        ->visibility('public')
                                        ->image()
                                        ->optimize('webp')
                                        ->required(),
                                    ])
                                    ->defaultItems(1)
                                    ->addActionLabel('Add Variant Photos')
                                    ->columnSpan(2)
                    ])
                    ->addActionLabel('Add More Product Variant')
                    ->grid(2)
                    ->columnSpanFull()
                    ->columns(1)
                    ->collapsible()
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
                // ViewColumn::make('productVariants')
                // ->label('Product Variants')
                // ->view('filament.adminPanel.product-variant')
                ])
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