@extends('layouts.shop')
@section('title', 'Track Order')
@section('content')
<div class="max-w-xl mx-auto px-4 py-10">
  <h1 class="text-2xl font-bold mb-4">Track Your Order</h1>
  <form method="POST" action="{{ route('track.check') }}" class="bg-white border rounded-xl p-5 space-y-3">@csrf
    <input name="order_no" value="{{ old('order_no') }}" placeholder="Order number (e.g. ORD260921ABCD)" class="w-full border-gray-300 rounded-lg">
    <input name="phone" value="{{ old('phone') }}" placeholder="Phone number used for the order" class="w-full border-gray-300 rounded-lg">
    @error('order_no')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    <button class="bg-pink-600 text-white px-6 py-2 rounded-lg">Track</button>
  </form>
  @if($order)
  <div class="bg-white border rounded-xl p-5 mt-6">
    <p class="font-semibold">{{ $order->order_no }} <span class="text-sm text-gray-500">· {{ $order->created_at->format('d M Y, h:i A') }}</span></p>
    @if($order->status === 'cancelled')<p class="mt-3 text-red-600 font-semibold">This order was cancelled.</p>
    @else
      @php $steps = ['pending', 'confirmed', 'shipped', 'delivered']; $cur = array_search($order->status, $steps); @endphp
      <div class="flex justify-between mt-5">@foreach($steps as $k => $st)
        <div class="text-center flex-1"><div class="mx-auto w-8 h-8 rounded-full flex items-center justify-center text-white {{ $k <= $cur ? 'bg-pink-600' : 'bg-gray-300' }}">{{ $k + 1 }}</div><p class="text-xs mt-1 capitalize">{{ $st }}</p></div>@endforeach</div>
    @endif
    <ul class="mt-5 text-sm divide-y">@foreach($order->items as $i)<li class="flex justify-between py-2"><span>{{ $i->name }} × {{ $i->qty }}</span><span>৳{{ number_format($i->price * $i->qty) }}</span></li>@endforeach</ul>
    <p class="text-right font-bold mt-2">Total: ৳{{ number_format($order->total) }}</p>
  </div>@endif
</div>
@endsection
