@extends('layouts.shop')
@section('title', 'My Wishlist')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-8"><h1 class="text-2xl font-bold mb-4">My Wishlist</h1>
  @if($products->count())<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">@include('partials.product-cards')</div>
  @else<p class="text-gray-500 py-16 text-center">Your wishlist is empty.</p>@endif</div>
@endsection
