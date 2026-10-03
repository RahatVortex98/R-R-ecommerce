@extends('layouts.shop')
@section('title', 'Checkout')
@section('content')
<div class="max-w-6xl mx-auto px-4 py-8" x-data="{area:'{{ old('area', 'inside_dhaka') }}', pay:'{{ old('payment_method', 'cod') }}',
  ship:{inside_dhaka:{{ $cart->shipping('inside_dhaka') }}, outside_dhaka:{{ $cart->shipping('outside_dhaka') }}}, sub:{{ $cart->subtotal() }}, disc:{{ $cart->discount() }}}">
  <h1 class="text-2xl font-bold mb-4">Checkout</h1>
  @if($errors->any())<div class="bg-red-100 text-red-800 rounded p-3 mb-4 text-sm"><ul class="list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  <div class="grid lg:grid-cols-3 gap-6">
    <form id="checkout-form" method="POST" action="{{ route('checkout.store') }}" class="lg:col-span-2 bg-white border rounded-xl p-5 space-y-4">@csrf
      <div class="grid md:grid-cols-2 gap-4">
        <div><label class="text-sm font-medium">Full name *</label><input name="name" value="{{ old('name', $user?->name) }}" required class="w-full border-gray-300 rounded-lg"></div>
        <div><label class="text-sm font-medium">Mobile number *</label><input name="phone" value="{{ old('phone', $user?->phone) }}" required placeholder="01XXXXXXXXX" class="w-full border-gray-300 rounded-lg"></div>
      </div>
      <div><label class="text-sm font-medium">Email (optional)</label><input name="email" type="email" value="{{ old('email', $user?->email) }}" class="w-full border-gray-300 rounded-lg"></div>
      <div><label class="text-sm font-medium">Full address *</label><textarea name="address" rows="3" required class="w-full border-gray-300 rounded-lg">{{ old('address', $user?->address) }}</textarea></div>
      <div><p class="text-sm font-medium mb-1">Delivery area</p>
        <label class="mr-4"><input type="radio" name="area" value="inside_dhaka" x-model="area"> Inside Dhaka</label>
        <label><input type="radio" name="area" value="outside_dhaka" x-model="area"> Outside Dhaka</label></div>
      <div><p class="text-sm font-medium mb-1">Payment</p>
        <label class="mr-4"><input type="radio" name="payment_method" value="cod" x-model="pay"> Cash on delivery</label>
        <label><input type="radio" name="payment_method" value="bkash_manual" x-model="pay"> bKash</label>
        <div x-show="pay==='bkash_manual'" x-cloak class="mt-3 bg-pink-50 border border-pink-200 rounded-lg p-3 text-sm space-y-2">
          <p>Send the total amount via bKash <b>Send Money</b> to <b>{{ \App\Models\Setting::get('bkash_number', '01XXXXXXXXX') }}</b>, then enter the Transaction ID:</p>
          <input name="trx_id" value="{{ old('trx_id') }}" placeholder="bKash TrxID" class="w-full border-gray-300 rounded-lg"></div></div>
      <div><label class="text-sm font-medium">Order note (optional)</label><textarea name="note" rows="2" class="w-full border-gray-300 rounded-lg">{{ old('note') }}</textarea></div>
    </form>

    <aside class="bg-white border rounded-xl p-5 h-fit space-y-3 text-sm">
      <h2 class="font-semibold text-base">Order Summary</h2>
      @foreach($cart->items() as $i)<div class="flex justify-between gap-2"><span>{{ $i['product']->name }} × {{ $i['qty'] }}</span><span>৳{{ number_format($i['total']) }}</span></div>@endforeach
      <hr>
      @if($cart->coupon())
        <div class="flex justify-between text-pink-600"><span>Coupon {{ $cart->coupon()->code }}</span>
          <form method="POST" action="{{ route('coupon.remove') }}">@csrf @method('DELETE')<button class="underline">remove</button></form></div>
      @else
        <form method="POST" action="{{ route('coupon.apply') }}" class="flex gap-2">@csrf
          <input name="code" placeholder="Coupon code" class="flex-1 border-gray-300 rounded-lg text-sm"><button class="bg-gray-800 text-white px-3 rounded-lg">Apply</button></form>
      @endif
      <div class="flex justify-between"><span>Subtotal</span><span>৳<span x-text="sub.toLocaleString()"></span></span></div>
      <div class="flex justify-between text-pink-600"><span>Discount</span><span>−৳<span x-text="disc.toLocaleString()"></span></span></div>
      <div class="flex justify-between"><span>Shipping</span><span>৳<span x-text="ship[area]"></span></span></div>
      <div class="flex justify-between font-bold text-base border-t pt-2"><span>Total</span><span>৳<span x-text="(sub-disc+ship[area]).toLocaleString()"></span></span></div>
      <button form="checkout-form" class="w-full bg-pink-600 text-white py-3 rounded-lg font-semibold hover:bg-pink-700">Place Order</button>
    </aside>
  </div>
</div>
@endsection
