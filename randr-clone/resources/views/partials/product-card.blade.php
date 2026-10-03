
<div class="mt-1">
  @if($p->has_variants)<span class="text-xs text-gray-500">From</span>@endif
  <span class="font-bold text-pink-600">৳{{ number_format($p->price_from) }}</span>
  @if(!$p->has_variants && $p->discount_percent)<span class="text-xs text-gray-400 line-through ml-1">৳{{ number_format($p->price) }}</span>@endif
</div>
@if($p->total_stock > 0)
  @if($p->has_variants)
    <a href="{{ route('product.show', $p) }}" class="mt-auto pt-2 block text-center bg-pink-600 text-white rounded-lg py-1.5 text-sm hover:bg-pink-700">Choose option</a>
  @else
    <div class="mt-auto pt-2 grid grid-cols-2 gap-2 text-sm">
      <button @click="$store.cart.add({{ $p->id }})" class="border border-pink-600 text-pink-600 rounded-lg py-1.5 hover:bg-pink-50">Add To Cart</button>
      <a href="{{ route('buy.now', $p) }}" class="bg-pink-600 text-white rounded-lg py-1.5 text-center hover:bg-pink-700">Buy now</a>
    </div>
  @endif
@else<p class="mt-auto pt-2 text-sm text-red-500 text-center">Out of stock</p>@endif
