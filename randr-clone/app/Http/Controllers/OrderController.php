<?php
namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function success(string $orderNo)
    {
        abort_unless(session('last_order') === $orderNo, 404);
        return view('order-success', ['order' => Order::with('items')->where('order_no', $orderNo)->firstOrFail()]);
    }
    public function trackForm() { return view('track', ['order' => null]); }
    public function track(Request $r)
    {
        $d = $r->validate(['order_no' => 'required', 'phone' => 'required']);
        $order = Order::with('items')->where('order_no', trim($d['order_no']))
            ->where('phone', preg_replace('/^\+?88/', '', trim($d['phone'])))->first();
        if (!$order) return back()->withInput()->withErrors(['order_no' => 'Order not found. Check the order number and phone.']);
        return view('track', compact('order'));
    }
}