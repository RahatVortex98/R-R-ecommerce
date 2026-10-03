@extends('layouts.shop')
@section('title', 'Order Placed')
@section('content')
<div class="max-w-xl mx-auto px-4 py-16 text-center">
  <div class="text-6xl">✅</div>
  <h1 class="text-2xl font-bold mt-4">Thank you! Your order is placed.</h1>
  <p class="mt-2 text-gray-600">Order number: <b>{{ $order->order_no }}</b></p>
  <p class="text-gray-600">Total: <b>৳{{ number_format($order->total) }}</b> ({{ $order->payment_method === 'cod' ? 'Cash on delivery' : 'bKash — we will verify your TrxID' }})</p>
  <div class="mt-6 flex justify-center gap-3"><a href="{{ route('track') }}" class="border border-pink-600 text-pink-600 px-5 py-2 rounded-lg">Track order</a><a href="{{ route('home') }}" class="bg-pink-600 text-white px-5 py-2 rounded-lg">Continue shopping</a></div>
</div>
@endsection
