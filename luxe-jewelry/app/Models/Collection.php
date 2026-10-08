<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

final class Collection extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'tagline', 'description', 'feature_image', 'is_featured', 'position'];

    protected function casts(): array
    {
        return ['is_featured' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::creating(function (Collection $collection): void {
            if (blank($collection->slug)) {
                $base = Str::slug($collection->name) ?: 'collection';
                $slug = $base;
                $i = 2;
                while (static::where('slug', $slug)->exists()) {
                    $slug = "{$base}-{$i}";
                    $i++;
                }
                $collection->slug = $slug;
            }
            if (blank($collection->position)) {
                $collection->position = (int) static::max('position') + 1;
            }
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true)->orderBy('position');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
