@extends('admin.layout')
@section('content')
<a href="{{ route('admin.orders.index') }}" class="text-blue-600 text-sm">← Orders</a>
<h1 class="text-xl font-semibold my-3">Order {{ $order->order_no }}</h1>
<div class="grid lg:grid-cols-3 gap-4">
  <div class="lg:col-span-2 bg-white rounded-lg shadow p-4">
    <table class="w-full text-sm"><thead class="text-left"><tr><th>Item</th><th>Price</th><th>Qty</th><th class="text-right">Total</th></tr></thead>
      @foreach($order->items as $i)<tr class="border-t"><td class="py-2">{{ $i->name }}</td><td>৳{{ number_format($i->price) }}</td><td>{{ $i->qty }}</td><td class="text-right">৳{{ number_format($i->price * $i->qty) }}</td></tr>@endforeach</table>
    <div class="text-right text-sm mt-3 space-y-1"><p>Subtotal ৳{{ number_format($order->subtotal) }}</p><p>Discount −৳{{ number_format($order->discount) }} {{ $order->coupon_code ? '('.$order->coupon_code.')' : '' }}</p><p>Shipping ৳{{ number_format($order->shipping) }}</p><p class="font-bold text-base">Total ৳{{ number_format($order->total) }}</p></div>
  </div>
  <div class="space-y-4">
    <div class="bg-white rounded-lg shadow p-4 text-sm space-y-1"><p class="font-semibold">Customer</p><p>{{ $order->name }}</p><p>{{ $order->phone }}</p><p>{{ $order->email }}</p><p>{{ $order->address }}</p><p class="capitalize">{{ str_replace('_', ' ', $order->area) }}</p>
      <p class="pt-2">Payment: {{ $order->payment_method }} @if($order->trx_id)· TrxID <b>{{ $order->trx_id }}</b>@endif</p>@if($order->note)<p>Note: {{ $order->note }}</p>@endif</div>
    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="bg-white rounded-lg shadow p-4 space-y-3 text-sm">@csrf @method('PATCH')
      <label class="block">Status<select name="status" class="w-full border-gray-300 rounded">@foreach(\App\Models\Order::STATUSES as $s)<option value="{{ $s }}" @selected($order->status === $s)>{{ ucfirst($s) }}</option>@endforeach</select></label>
      <label class="block">Payment<select name="payment_status" class="w-full border-gray-300 rounded">@foreach(['unpaid', 'paid'] as $s)<option value="{{ $s }}" @selected($order->payment_status === $s)>{{ ucfirst($s) }}</option>@endforeach</select></label>
      <button class="bg-pink-600 text-white px-4 py-2 rounded">Update</button></form>
  </div>
</div>
@endsection
