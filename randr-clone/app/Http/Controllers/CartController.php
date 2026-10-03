<?php
namespace App\Http\Controllers;
use App\Models\{Coupon, Product};
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function json(CartService $c) { return response()->json(['count' => $c->count(), 'html' => view('partials.cart-items', ['cart' => $c])->render()]); }
    public function drawer(CartService $c) { return $this->json($c); }
    public function add(Request $r, CartService $c)
    {
        $r->validate(['product_id' => 'required|exists:products,id', 'variant_id' => 'nullable|integer', 'qty' => 'nullable|integer|min:1|max:99']);
        $c->add($r->product_id, (int) $r->input('variant_id', 0), $r->integer('qty', 1));
        return $this->json($c);
    }
    public function update(Request $r, CartService $c)
    {
        $r->validate(['key' => 'required|string', 'qty' => 'required|integer|min:0|max:99']);
        $c->setByKey($r->key, $r->qty);
        return $this->json($c);
    }
    public function remove(Request $r, CartService $c) { $c->setByKey($r->key, 0); return $this->json($c); }
    public function buyNow(Request $r, Product $product, CartService $c)
    {
        abort_unless($product->is_active, 404);
        $c->add($product->id, (int) $r->input('variant_id', 0), max(1, $r->integer('qty', 1)));
        return redirect()->route('checkout');
    }
    public function applyCoupon(Request $r, CartService $c)
    {
        $r->validate(['code' => 'required|string']);
        $coupon = Coupon::where('code', strtoupper(trim($r->code)))->first();
        if (!$coupon) return back()->with('error', 'Invalid coupon code.');
        if ($err = $coupon->check($c->subtotal())) return back()->with('error', $err);
        session(['coupon' => $coupon->code]);
        return back()->with('ok', 'Coupon applied!');
    }
    public function removeCoupon() { session()->forget('coupon'); return back(); }
}