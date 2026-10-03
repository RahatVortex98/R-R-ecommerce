@extends('layouts.frontend')
@section('content')
<div class="container mx-auto mt-4 px-4">
    <img src="https://ghorerbazar.com/wp-content/uploads/2023/12/banner.jpg" class="w-full rounded-xl h-[400px] object-cover" alt="Banner">
</div>

<section class="container mx-auto px-4 mt-8">
    <h2 class="text-2xl font-bold mb-4">Shop by Category</h2>
    <div class="grid grid-cols-3 md:grid-cols-6 gap-4">
        @foreach($categories as $cat)
        <a href="{{ route('category.show', $cat->slug) }}" class="bg-white p-4 rounded-xl shadow text-center hover:shadow-lg">
            <img src="{{ asset('storage/' . $cat->image) }}" class="w-20 h-20 mx-auto object-contain" alt="{{ $cat->name }}">
            <p class="mt-2 font-medium">{{ $cat->name }}</p>
        </a>
        @endforeach
    </div>
</section>

<section class="container mx-auto px-4 mt-10">
    <h2 class="text-2xl font-bold mb-4">Best Selling Products</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
        @foreach($bestSellers as $product)
        <div class="bg-white rounded-xl shadow p-3">
            <img src="{{ asset('storage/' . $product->thumbnail) }}" class="h-48 w-full object-cover rounded-lg" alt="{{ $product->name }}">
            <h3 class="font-semibold mt-2 line-clamp-2">{{ $product->name }} - {{ $product->weight }}</h3>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-[#6a9732] font-bold">৳{{ $product->discount_price ?? $product->price }}</span>
                @if($product->discount_price)
                <span class="line-through text-sm text-gray-400">৳{{ $product->price }}</span>
                @endif
            </div>
            <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="w-full bg-[#6a9732] text-white py-2 rounded-full font-medium">Add to Cart</button>
            </form>
        </div>
        @endforeach
    </div>
</section>
@endsection
