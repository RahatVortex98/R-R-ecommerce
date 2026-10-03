<?php
namespace App\Services;
use App\Models\{Coupon, Product, Setting};
use Illuminate\Support\Collection;

class CartService
{
    private ?Collection $memo = null;
    public function raw(): array { return session('cart', []); } // key "productId:variantId" => qty
    private function save(array $c): void { session(['cart' => $c]); $this->memo = null; }
    private function key(int $pid, int $vid = 0): string { return "$pid:$vid"; }

    public function add(int $productId, int $variantId = 0, int $qty = 1): void
    {
        $k = $this->key($productId, $variantId);
        $c = $this->raw(); $c[$k] = ($c[$k] ?? 0) + $qty; $this->save($c);
    }
    public function setByKey(string $key, int $qty): void
    {
        $c = $this->raw();
        if ($qty > 0) $c[$key] = $qty; else unset($c[$key]);
        $this->save($c);
    }
    public function clear(): void { session()->forget(['cart', 'coupon']); $this->memo = null; }

    public function items(): Collection
    {
        return $this->memo ??= (function () {
            $raw = $this->raw();
            $pids = collect(array_keys($raw))->map(fn ($k) => (int) explode(':', $k)[0])->unique();
            $products = Product::active()->with('variants')->whereIn('id', $pids)->get()->keyBy('id');

            return collect($raw)->map(function ($qty, $key) use ($products) {
                [$pid, $vid] = array_map('intval', explode(':', $key));
                $p = $products[$pid] ?? null;
                if (!$p) return null;
                $variant = $vid ? $p->variants->firstWhere('id', $vid) : null;
                if ($vid && !$variant) return null;
                $stock = $variant ? $variant->stock : $p->stock;
                if ($stock < 1) return null;
                $q = min($qty, $stock);
                $price = $variant ? $variant->current_price : $p->current_price;
                return [
                    'key' => $key, 'product' => $p, 'variant' => $variant, 'qty' => $q,
                    'price' => $price, 'total' => $price * $q,
                    'name' => $p->name . ($variant ? ' — ' . $variant->name : ''),
                ];
            })->filter()->values();
        })();
    }

    public function count(): int { return (int) $this->items()->sum('qty'); }
    public function subtotal(): int { return (int) $this->items()->sum('total'); }
    public function coupon(): ?Coupon
    {
        $code = session('coupon');
        if (!$code) return null;
        $c = Coupon::where('code', $code)->first();
        return ($c && !$c->check($this->subtotal())) ? $c : null;
    }
    public function discount(): int { return $this->coupon()?->discountFor($this->subtotal()) ?? 0; }
    public function shipping(string $area): int
    {
        $free = (int) Setting::get('free_shipping_over', 0);
        if ($free > 0 && ($this->subtotal() - $this->discount()) >= $free) return 0;
        return $area === 'inside_dhaka' ? (int) Setting::get('shipping_inside_dhaka', 60) : (int) Setting::get('shipping_outside_dhaka', 120);
    }
}