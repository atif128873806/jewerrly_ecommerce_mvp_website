<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'email', 'first_name', 'last_name', 'phone',
        'address', 'address_line2', 'city', 'postal_code', 'country', 'notes',
        'payment_method', 'payment_status', 'status',
        'subtotal', 'shipping', 'total',
    ];

    protected function casts(): array
    {
        return ['payment_method' => PaymentMethod::class];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    protected function fullName(): Attribute
    {
        return Attribute::get(fn (): string => trim("{$this->first_name} {$this->last_name}"));
    }

    public function getRouteKeyName(): string
    {
        return 'order_number';
    }
}
