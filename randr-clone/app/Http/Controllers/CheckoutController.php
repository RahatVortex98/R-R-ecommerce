<?php
namespace App\Http\Controllers;
use App\Models\{Order, Product};
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function show(CartService $cart)
    {
        if ($cart->items()->isEmpty()) return redirect()->route('shop')->with('error', 'Your cart is empty.');
        return view('checkout', ['cart' => $cart, 'user' => auth()->user()]);
    }

    public function store(Request $r, CartService $cart)
    {
        $d = $r->validate([
            'name' => 'required|max:120',
            'phone' => ['required', 'regex:/^(?:\+?88)?01[3-9]\d{8}$/'],
            'email' => 'nullable|email',
            'address' => 'required|max:500',
            'area' => 'required|in:inside_dhaka,outside_dhaka',
            'payment_method' => 'required|in:cod,bkash_manual',
            'trx_id' => 'required_if:payment_method,bkash_manual|nullable|max:50',
            'note' => 'nullable|max:500',
        ], ['phone.regex' => 'Enter a valid Bangladeshi mobile number (01XXXXXXXXX).']);
        $d['phone'] = preg_replace('/^\+?88/', '', $d['phone']);
        if ($cart->items()->isEmpty()) return redirect()->route('shop');

        $order = DB::transaction(function () use ($d, $cart) {
            $items = $cart->items();
            foreach ($items as $i) {
                $p = Product::lockForUpdate()->find($i['product']->id);
                if ($p->stock < $i['qty']) throw ValidationException::withMessages(['cart' => "{$p->name} is out of stock."]);
                $p->decrement('stock', $i['qty']);
                $p->increment('sold_count', $i['qty']);
            }
            $sub = $cart->subtotal(); $coupon = $cart->coupon();
            $disc = $coupon ? $coupon->discountFor($sub) : 0;
            $ship = $cart->shipping($d['area']);
            $order = Order::create($d + [
                'order_no' => 'ORD'.now()->format('ymd').strtoupper(Str::random(4)),
                'user_id' => auth()->id(), 'subtotal' => $sub, 'shipping' => $ship, 'discount' => $disc,
                'total' => $sub - $disc + $ship, 'coupon_code' => $coupon?->code,
            ]);
            foreach ($items as $i) {
                $order->items()->create(['product_id' => $i['product']->id, 'name' => $i['product']->name, 'price' => $i['product']->current_price, 'qty' => $i['qty']]);
            }
            $coupon?->increment('used');
            return $order;
        });

        $cart->clear();
        session(['last_order' => $order->order_no]);
        return redirect()->route('order.success', $order->order_no);
    }
}