<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $guarded = [];
    protected $casts = ['expires_at'=>'datetime','is_active'=>'boolean'];
    public function check(int $subtotal): ?string
    {
        if (!$this->is_active) return 'This coupon is not active.';
        if ($this->expires_at && $this->expires_at->isPast()) return 'This coupon has expired.';
        if ($this->max_uses && $this->used >= $this->max_uses) return 'Coupon usage limit reached.';
        if ($subtotal < $this->min_order) return 'Minimum order ৳'.number_format($this->min_order).' required.';
        return null;
    }
    public function discountFor(int $subtotal): int
    {
        $d = $this->type === 'percent' ? intdiv($subtotal * $this->value, 100) : $this->value;
        return min($d, $subtotal);
    }
}