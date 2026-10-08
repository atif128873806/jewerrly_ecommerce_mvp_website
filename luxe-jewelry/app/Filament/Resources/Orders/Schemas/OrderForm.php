<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\PaymentMethod;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order')
                    ->columns(2)
                    ->schema([
                        TextInput::make('order_number')
                            ->disabled(),
                        TextInput::make('created_at')
                            ->label('Placed at')
                            ->formatStateUsing(fn ($state) => filled($state) ? Carbon::parse($state)->format('d M Y, H:i') : null)
                            ->disabled(),
                        Select::make('status')
                            ->label('Order status')
                            ->options([
                                'confirmed' => 'Confirmed',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->native(false),
                        Select::make('payment_status')
                            ->label('Payment status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'refunded' => 'Refunded',
                            ])
                            ->required()
                            ->native(false),
                        TextInput::make('payment_method')
                            ->label('Payment method')
                            ->formatStateUsing(function ($state) {
                                $pm = $state instanceof PaymentMethod ? $state : PaymentMethod::tryFrom((string) $state);

                                return $pm?->label() ?? $state;
                            })
                            ->disabled(),
                    ]),

                Section::make('Customer')
                    ->columns(2)
                    ->schema([
                        TextInput::make('first_name')->disabled(),
                        TextInput::make('last_name')->disabled(),
                        TextInput::make('email')->disabled(),
                        TextInput::make('phone')->disabled()->placeholder('—'),
                    ]),

                Section::make('Shipping address')
                    ->columns(2)
                    ->schema([
                        TextInput::make('address')->disabled()->columnSpanFull(),
                        TextInput::make('address_line2')->label('Address line 2')->disabled()->placeholder('—')->columnSpanFull(),
                        TextInput::make('city')->disabled(),
                        TextInput::make('postal_code')->disabled(),
                        TextInput::make('country')->disabled(),
                    ]),

                Section::make('Summary')
                    ->columns(3)
                    ->schema([
                        TextInput::make('subtotal')
                            ->formatStateUsing(fn ($state) => money((int) $state))
                            ->disabled(),
                        TextInput::make('shipping')
                            ->formatStateUsing(fn ($state) => ((int) $state) === 0 ? 'Free' : money((int) $state))
                            ->disabled(),
                        TextInput::make('total')
                            ->formatStateUsing(fn ($state) => money((int) $state))
                            ->disabled(),
                        Textarea::make('notes')
                            ->label('Customer notes')
                            ->disabled()
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
