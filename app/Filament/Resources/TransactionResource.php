<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use App\Services\WilayahService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {


        return $table
            ->columns([
                TextColumn::make('order_id')
                ->label('Order ID')
                ->searchable(),

                // CUSTOMER INFO
                ViewColumn::make('user')
                ->label('Customer')
                ->view('filament.adminPanel.transaction.customer-info'),

                // PRODUCT INFO
                // TextColumn::make('transactionItems.productVariant.product.name')
                // ->label('Product')
                // ->searchable(),

                ViewColumn::make('transactionItems')
                ->label('Product')
                ->view('filament.adminPanel.transaction.product-info'),
                
                TextColumn::make('transactionItems.price')
                ->label('Harga (IDR)')
                ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                ->alignCenter(),
                TextColumn::make('transactionItems.quantity')
                ->label('Quantity')
                ->alignCenter(),
                TextColumn::make('total_price')
                ->label('Total Payment (IDR)')
                ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                ->alignCenter(),
                TextColumn::make('status')
                ->label('Payment Status')
                ->badge()
                ->color(fn(string $state):string => match ($state){
                    'pending' => 'warning',
                    'paid' => 'success',
                    'settlement' => 'success',
                    // next time add for cancel
                })
                ->formatStateUsing(fn($state) => ucfirst($state))
                ->alignCenter(),
                TextColumn::make('payment_type')
                ->label('Payment Type')
                ->formatStateUsing(fn($state) => strtoupper($state))
                ->alignCenter(),
                TextColumn::make('paid_at')
                ->label('Waktu Pembayaran')
                ->dateTime()
                ->alignCenter(),


                TextColumn::make('address.provinsi_name')
                ->label('Provinsi')
                ->searchable(),
                TextColumn::make('address.kabupaten_name')
                ->label('Kabupaten')
                ->searchable(),
                TextColumn::make('address.kecamatan_name')
                ->label('Kecamatan')
                ->searchable(),
                TextColumn::make('address.kelurahan_name')
                ->label('Kelurahan')
                ->searchable(),
                TextColumn::make('address.postal_code')
                ->label('Kode Pos')
                ->alignCenter()
                ->searchable(),
                TextColumn::make('address.detail')
                ->label('Detail Alamat'),
            
                

            ])
            ->striped()
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                ViewAction::make()
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}