<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'name_en', 'name_fr',
        'slug', 'description', 'description_en', 'description_fr',
        'short_description', 'short_description_en', 'short_description_fr',
        'price', 'sale_price', 'sku', 'stock', 'type', 'weight', 'length', 'width', 'height',
        'low_stock_threshold', 'shipping_company_id',
        'status', 'featured', 'seo_title', 'seo_description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'weight' => 'decimal:3',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'stock' => 'integer',
        'low_stock_threshold' => 'integer',
        'featured' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            if (empty($product->sku)) {
                $product->sku = 'SKU-' . strtoupper(Str::random(8));
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function shippingCompany(): BelongsTo
    {
        return $this->belongsTo(ShippingCompany::class, 'shipping_company_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function options(): HasMany
    {
        return $this->hasMany(ProductOption::class)->orderBy('order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function customFields(): HasMany
    {
        return $this->hasMany(ProductCustomField::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function shippingRule(): HasOne
    {
        return $this->hasOne(ProductShippingRule::class);
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('status', 'active');
    }

    public function scopeFeatured(Builder $q): Builder
    {
        return $q->where('featured', true);
    }

    public function scopeInStock(Builder $q): Builder
    {
        return $q->where('stock', '>', 0);
    }

    public function getFinalPriceAttribute(): float
    {
        // Use sale_price only if it's set AND less than the regular price (valid discount)
        if ($this->sale_price !== null && (float) $this->sale_price > 0 && (float) $this->sale_price < (float) $this->price) {
            return (float) $this->sale_price;
        }
        return (float) $this->price;
    }

    public function getDiscountPercentAttribute(): int
    {
        if (!$this->sale_price || $this->price <= 0) return 0;
        return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    public function getAverageRatingAttribute(): float
    {
        return (float) $this->reviews()->avg('rating') ?: 0;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        return match ($locale) {
            'en' => $this->attributes['name_en'] ?? $this->attributes['name'],
            'fr' => $this->attributes['name_fr'] ?? $this->attributes['name'],
            default => $this->attributes['name'],
        };
    }

    public function getDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        return match ($locale) {
            'en' => $this->attributes['description_en'] ?? $this->attributes['description'],
            'fr' => $this->attributes['description_fr'] ?? $this->attributes['description'],
            default => $this->attributes['description'],
        };
    }

    public function getShortDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        return match ($locale) {
            'en' => $this->attributes['short_description_en'] ?? $this->attributes['short_description'],
            'fr' => $this->attributes['short_description_fr'] ?? $this->attributes['short_description'],
            default => $this->attributes['short_description'],
        };
    }

    public function getLocalizedName(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        return match ($locale) {
            'en' => $this->attributes['name_en'] ?? $this->attributes['name'],
            'fr' => $this->attributes['name_fr'] ?? $this->attributes['name'],
            default => $this->attributes['name'],
        };
    }
}
