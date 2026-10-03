@extends('layouts.frontend')
@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">Your Cart</h2>
    @foreach($carts as $cart)
    <div class="bg-white p-4 rounded-xl shadow flex justify-between items-center mb-3">
        <div class="flex gap-4 items-center">
            <img src="{{ asset('storage/' . $cart->product->thumbnail) }}" class="w-20 h-20 rounded">
            <div>
                <h3>{{ $cart->product->name }}</h3>
                <p class="text-[#6a9732] font-bold">৳{{ $cart->product->discount_price ?? $cart->product->price }} x {{ $cart->quantity }}</p>
            </div>
        </div>
        <form action="{{ route('cart.remove', $cart->id) }}" method="POST">
            @csrf 
            @method('DELETE')
            <button class="text-red-500">Remove</button>
        </form>
    </div>
    @endforeach
    <a href="{{ route('checkout.index') }}" class="bg-[#6a9732] text-white px-8 py-3 rounded-full inline-block mt-4">Proceed to Checkout</a>
</div>
@endsection
