<?php

namespace App\Filament\Resources\Products\Tables;

use App\Enums\Material;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label('')
                    ->disk('webroot')
                    ->height(56)
                    ->width(46),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->sku),
                TextColumn::make('category.name')
                    ->badge()
                    ->sortable(),
                TextColumn::make('collection.name')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),
                TextColumn::make('price')
                    ->label('Price')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => money($state)),
                TextColumn::make('stock_qty')
                    ->label('Stock')
                    ->sortable()
                    ->alignCenter(),
                ToggleColumn::make('in_stock')
                    ->label('Available'),
                IconColumn::make('is_featured')
                    ->label('Feat.')
                    ->boolean()
                    ->alignCenter(),
                IconColumn::make('is_new')
                    ->label('New')
                    ->boolean()
                    ->alignCenter(),
            ])
            ->defaultSort('position')
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('collection')
                    ->relationship('collection', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('material')
                    ->options(collect(Material::cases())->mapWithKeys(fn (Material $m) => [$m->value => $m->label()])),
                TernaryFilter::make('in_stock')->label('Availability'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
