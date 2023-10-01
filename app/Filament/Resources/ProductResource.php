<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;;
use Filament\Support\Enums\FontWeight;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $title = 'Productos';
    protected static ?string $navigationLabel = 'Productos';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('product_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('store_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('store_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('brand')
                    ->maxLength(255),
                Forms\Components\TextInput::make('model')
                    ->maxLength(255),
                Forms\Components\TextInput::make('url')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image_url')
                    ->image()
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('created_cocacola')
                    ->required(),
                Forms\Components\DateTimePicker::make('updated_cocacola')
                    ->required(),
                Forms\Components\DateTimePicker::make('extracted')
                    ->required(),
                Forms\Components\TextInput::make('lowest_price')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('offer_price')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('normal_price')
                    ->numeric(),
                Forms\Components\TextInput::make('warehouse_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('warehouse_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\ImageColumn::make('image_url')->circular(),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')->weight(FontWeight::Bold),
                        Tables\Columns\TextColumn::make('sku'),
                        Tables\Columns\TextColumn::make('store_name'),
                    ]),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('brand'),
                        Tables\Columns\TextColumn::make('model'),
                        Tables\Columns\TextColumn::make('status'),
                    ]),
                ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
