@extends('layouts.shop')
@section('title', $product->name)
@section('description', \Illuminate\Support\Str::limit(strip_tags($product->description), 150))
@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
  <p class="text-sm text-gray-500"><a href="{{ route('home') }}">Home</a> / <a href="{{ route('collection', $product->category) }}">{{ $product->category->name }}</a> / {{ $product->name }}</p>
  <div class="grid md:grid-cols-2 gap-8 mt-4 bg-white border rounded-2xl p-5" x-data="{qty:1}">
    <div class="relative"><img src="{{ $product->image_url }}" class="w-full rounded-xl aspect-square object-cover">
      @if($product->badge)<span class="absolute top-3 left-3 bg-pink-600 text-white text-xs px-2 py-1 rounded">{{ $product->badge }}</span>@endif</div>
    <div>
      <h1 class="text-2xl font-bold">{{ $product->name }}</h1>
      <div class="mt-3"><span class="text-3xl font-bold text-pink-600">৳{{ number_format($product->current_price) }}</span>
        @if($product->discount_percent)<span class="ml-2 text-gray-400 line-through">৳{{ number_format($product->price) }}</span>@endif</div>
      <ul class="mt-3 text-sm text-gray-600 space-y-1">
        <li>Category: <a class="text-pink-600" href="{{ route('collection', $product->category) }}">{{ $product->category->name }}</a></li>
        @if($product->brand)<li>Brand: <a class="text-pink-600" href="{{ route('brand', $product->brand) }}">{{ $product->brand->name }}</a></li>@endif
        @if($product->sku)<li>SKU: {{ $product->sku }}</li>@endif
        <li>{!! $product->stock > 0 ? '<span class="text-pink-600">In stock</span>' : '<span class="text-red-600">Out of stock</span>' !!}</li>
      </ul>
      @if($product->stock > 0)
      <div class="flex items-center gap-3 mt-5">
        <div class="flex items-center border rounded-lg">
          <button class="px-3 py-2" @click="qty=Math.max(1,qty-1)">−</button><span class="px-3" x-text="qty"></span>
          <button class="px-3 py-2" @click="qty=Math.min({{ $product->stock }},qty+1)">+</button></div>
        <button @click="$store.cart.add({{ $product->id }}, qty)" class="flex-1 border border-pink-600 text-pink-600 rounded-lg py-2.5 hover:bg-pink-50">Add To Cart</button>
        <form method="POST" action="{{ route('buy.now', $product) }}" class="flex-1">@csrf<input type="hidden" name="qty" :value="qty"><button class="w-full bg-pink-600 text-white rounded-lg py-2.5 hover:bg-pink-700">Buy now</button></form>
      </div>@endif
      @auth<form method="POST" action="{{ route('wishlist.toggle', $product) }}" class="mt-3">@csrf<button class="text-sm text-red-500">{{ auth()->user()->wishlist->contains($product->id) ? '♥ Remove from wishlist' : '♡ Add to wishlist' }}</button></form>@endauth
    </div>
  </div>
  @if($product->description)<div class="mt-6 bg-white border rounded-2xl p-5"><h2 class="font-bold mb-2">Description</h2><div class="prose max-w-none text-gray-700 whitespace-pre-line">{{ $product->description }}</div></div>@endif
  @include('partials.section', ['title' => 'Related Products', 'products' => $related])
</div>
@endsection
