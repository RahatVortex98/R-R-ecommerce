<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $guarded = [];
    public function product() { return $this->belongsTo(Product::class); }
    public function getCurrentPriceAttribute(): int
    {
        $base = $this->price ?? $this->product->price;
        return ($this->sale_price && $this->sale_price < $base) ? $this->sale_price : $base;
    }
}