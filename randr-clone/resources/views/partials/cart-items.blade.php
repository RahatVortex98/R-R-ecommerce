@if($cart->items()->isEmpty())
  <div class="text-center p-10 text-gray-500">No items in your cart!</div>
@else
  <ul class="divide-y">
    @foreach($cart->items() as $i)
    <li class="p-4 flex gap-3">
      <img src="{{ $i['product']->image_url }}" class="w-16 h-16 rounded object-cover">
      <div class="flex-1 text-sm">
        <p class="font-medium line-clamp-2">{{ $i['name'] }}</p>
        <p class="text-pink-600">৳{{ number_format($i['price']) }}</p>
        <div class="flex items-center gap-2 mt-1">
          <button onclick="Alpine.store('cart').update('{{ $i['key'] }}', {{ $i['qty'] - 1 }})" class="w-6 h-6 border rounded">−</button>
          <span>{{ $i['qty'] }}</span>
          <button onclick="Alpine.store('cart').update('{{ $i['key'] }}', {{ $i['qty'] + 1 }})" class="w-6 h-6 border rounded">+</button>
        </div>
      </div>
      <button onclick="Alpine.store('cart').remove('{{ $i['key'] }}')" class="text-red-500 self-start">🗑</button>
    </li>
    @endforeach
  </ul>
  <div class="p-4 border-t sticky bottom-0 bg-white">
    <div class="flex justify-between font-semibold mb-3"><span>Subtotal</span><span>৳{{ number_format($cart->subtotal()) }}</span></div>
    <a href="{{ route('checkout') }}" class="block text-center bg-pink-600 text-white py-3 rounded-lg font-semibold">Checkout</a>
  </div>
@endif
