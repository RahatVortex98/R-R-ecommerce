<?php
namespace App\Models;
use App\Support\Media;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];
    protected $casts = ['is_best_selling'=>'boolean','is_new'=>'boolean','is_offer'=>'boolean','is_certified'=>'boolean','is_active'=>'boolean','flash_ends_at'=>'datetime'];
    public function getRouteKeyName() { return 'slug'; }
    public function category() { return $this->belongsTo(Category::class); }
    public function brand() { return $this->belongsTo(Brand::class); }
    public function scopeActive($q) { return $q->where('is_active', true); }
    public function getCurrentPriceAttribute(): int { return ($this->sale_price && $this->sale_price < $this->price) ? $this->sale_price : $this->price; }
    public function getDiscountPercentAttribute(): int { return $this->current_price < $this->price ? (int) round(($this->price - $this->current_price) / $this->price * 100) : 0; }
    public function getBadgeAttribute(): ?string
    {
        if ($this->discount_percent) return 'Save '.$this->discount_percent.'%';
        return $this->is_best_selling ? 'Best Selling' : ($this->is_new ? 'New Arrival' : ($this->is_offer ? 'Offered Items' : null));
    }
    public function getImageUrlAttribute() { return Media::url($this->image, $this->name); }
public function variants() { return $this->hasMany(ProductVariant::class)->orderBy('sort'); }

public function getHasVariantsAttribute(): bool
{
    return $this->relationLoaded('variants') ? $this->variants->isNotEmpty() : $this->variants()->exists();
}

public function getPriceFromAttribute(): int
{
    return $this->has_variants ? $this->variants->min('current_price') : $this->current_price;
}

public function getTotalStockAttribute(): int
{
    return $this->has_variants ? $this->variants->sum('stock') : $this->stock;
}



    }