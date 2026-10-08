<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Material;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

final class Product extends Model
{
    use HasFactory;

    /** Auto-fill slug, SKU and position when an admin creates a product. */
    protected static function booted(): void
    {
        static::creating(function (Product $product): void {
            if (blank($product->slug)) {
                $base = Str::slug($product->name) ?: 'product';
                $slug = $base;
                $i = 2;
                while (static::where('slug', $slug)->exists()) {
                    $slug = "{$base}-{$i}";
                    $i++;
                }
                $product->slug = $slug;
            }

            if (blank($product->sku)) {
                do {
                    $sku = 'LX-' . random_int(10000, 99999);
                } while (static::where('sku', $sku)->exists());
                $product->sku = $sku;
            }

            if (blank($product->position)) {
                $product->position = (int) static::max('position') + 1;
            }
        });
    }

    protected $fillable = [
        'name', 'slug', 'sku', 'description', 'price', 'compare_price',
        'category_id', 'collection_id', 'material', 'symbol',
        'is_featured', 'is_new', 'is_bestseller', 'in_stock', 'stock_qty',
        'rating', 'reviews_count', 'position',
    ];

    protected function casts(): array
    {
        return [
            'material' => Material::class,
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_bestseller' => 'boolean',
            'in_stock' => 'boolean',
            'rating' => 'float',
        ];
    }

    /* ---------------- Relationships ---------------- */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    /* ---------------- Scopes ---------------- */

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('in_stock', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeNew(Builder $query): Builder
    {
        return $query->where('is_new', true);
    }

    public function scopeBestseller(Builder $query): Builder
    {
        return $query->where('is_bestseller', true);
    }

    /** Eager-load the primary image + category for cards. */
    public function scopeForCards(Builder $query): Builder
    {
        return $query->with(['primaryImage', 'category']);
    }

    public function primaryImage(): HasMany
    {
        return $this->hasMany(ProductImage::class)->where('is_primary', true);
    }

    /* ---------------- Accessors ---------------- */

    /** Price in euros (stored as cents). */
    protected function priceEur(): Attribute
    {
        return Attribute::get(fn (): float => $this->price / 100);
    }

    protected function comparePriceEur(): Attribute
    {
        return Attribute::get(fn (): ?float => $this->compare_price ? $this->compare_price / 100 : null);
    }

    protected function isOnSale(): Attribute
    {
        return Attribute::get(fn (): bool => $this->compare_price !== null && $this->compare_price > $this->price);
    }

    /** Convenience: the primary image path (falls back to first image). */
    protected function thumbnail(): Attribute
    {
        return Attribute::get(function (): ?string {
            $primary = $this->relationLoaded('primaryImage')
                ? $this->primaryImage->first()
                : $this->images()->where('is_primary', true)->first();

            return ($primary ?? $this->images->first())?->path;
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
