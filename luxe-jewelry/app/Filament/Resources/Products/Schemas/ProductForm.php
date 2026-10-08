<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\Material;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    /** 1 for a 0-decimal currency (PKR), 100 for a 2-decimal currency (EUR). */
    private static function priceDivisor(): int
    {
        return 10 ** (int) config('store.currency.decimals');
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->helperText('Leave blank to generate automatically from the name.')
                            ->maxLength(255),
                        TextInput::make('sku')
                            ->label('SKU')
                            ->helperText('Leave blank to generate automatically.')
                            ->maxLength(255),
                    ]),

                Section::make('Pricing & inventory')
                    ->columns(2)
                    ->schema([
                        TextInput::make('price')
                            ->label('Price (' . config('store.currency.symbol') . ')')
                            ->numeric()
                            ->required()
                            ->prefix(config('store.currency.symbol'))
                            ->minValue(0)
                            ->formatStateUsing(fn ($state) => $state !== null ? $state / self::priceDivisor() : null)
                            ->dehydrateStateUsing(fn ($state) => (int) round(((float) $state) * self::priceDivisor())),
                        TextInput::make('compare_price')
                            ->label('Compare-at price (' . config('store.currency.symbol') . ')')
                            ->helperText('Optional. Shows a struck-through "was" price and a Sale badge.')
                            ->numeric()
                            ->prefix(config('store.currency.symbol'))
                            ->minValue(0)
                            ->formatStateUsing(fn ($state) => filled($state) ? $state / self::priceDivisor() : null)
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? (int) round(((float) $state) * self::priceDivisor()) : null),
                        TextInput::make('stock_qty')
                            ->label('Stock quantity')
                            ->numeric()
                            ->default(25)
                            ->minValue(0),
                        Toggle::make('in_stock')
                            ->label('In stock')
                            ->default(true),
                    ]),

                Section::make('Organisation')
                    ->columns(2)
                    ->schema([
                        Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('collection_id')
                            ->label('Collection')
                            ->relationship('collection', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('None'),
                        Select::make('material')
                            ->options(collect(Material::cases())->mapWithKeys(fn (Material $m) => [$m->value => $m->label()]))
                            ->required()
                            ->default(Material::GoldPlated->value),
                        Select::make('symbol')
                            ->label('Meaning / symbol')
                            ->options([
                                'Love' => 'Love',
                                'Protection' => 'Protection',
                                'Balance' => 'Balance',
                                'Spirituality' => 'Spirituality',
                                'Healing' => 'Healing',
                                'Luck' => 'Luck',
                                'Strength' => 'Strength',
                            ])
                            ->placeholder('None'),
                    ]),

                Section::make('Visibility & rating')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_featured')->label('Featured'),
                        Toggle::make('is_new')->label('New in'),
                        Toggle::make('is_bestseller')->label('Bestseller'),
                        TextInput::make('rating')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(5)
                            ->step(0.1)
                            ->default(5.0),
                    ]),

                Section::make('Images')
                    ->description('Upload one or more photos. Mark one as the primary image shown on cards.')
                    ->schema([
                        Repeater::make('images')
                            ->relationship()
                            ->hiddenLabel()
                            ->schema([
                                FileUpload::make('path')
                                    ->label('Photo')
                                    ->image()
                                    ->disk('webroot')
                                    ->directory('img/products')
                                    ->visibility('public')
                                    ->imageEditor()
                                    ->required()
                                    ->columnSpan(2),
                                Toggle::make('is_primary')
                                    ->label('Primary image')
                                    ->inline(false),
                            ])
                            ->columns(3)
                            ->orderColumn('position')
                            ->reorderable()
                            ->collapsible()
                            ->defaultItems(1)
                            ->addActionLabel('Add another photo'),
                    ]),
            ]);
    }
}
