<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Order, Product};
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $r)
    {
        $orders = Order::when($r->status, fn ($q, $s) => $q->where('status', $s))
            ->when($r->q, fn ($q, $s) => $q->where(fn ($w) => $w->where('order_no', 'like', "%$s%")->orWhere('phone', 'like', "%$s%")->orWhere('name', 'like', "%$s%")))
            ->latest()->paginate(20)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }
    public function show(Order $order) { return view('admin.orders.show', ['order' => $order->load('items')]); }
    public function update(Request $r, Order $order)
    {
        $d = $r->validate(['status' => 'required|in:'.implode(',', Order::STATUSES), 'payment_status' => 'required|in:unpaid,paid']);
        if ($d['status'] === 'cancelled' && $order->status !== 'cancelled')
            foreach ($order->items as $i) if ($i->product_id) Product::whereKey($i->product_id)->increment('stock', $i->qty);
        if ($d['status'] !== 'cancelled' && $order->status === 'cancelled')
            foreach ($order->items as $i) if ($i->product_id) Product::whereKey($i->product_id)->decrement('stock', $i->qty);
        $order->update($d);
        return back()->with('ok', 'Order updated.');
    }
}