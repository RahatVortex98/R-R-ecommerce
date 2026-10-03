@extends(\'layouts.frontend\')
@section(\'content\')
<div class="container mx-auto px-4 py-8 grid md:grid-cols-2 gap-8">
    <form action="{{ route(\'checkout.store\') }}" method="POST" class="bg-white p-6 rounded-xl shadow">
        @csrf
        <h3 class="font-bold text-xl mb-4">Delivery Details</h3>
        <input name="name" placeholder="Full Name" class="w-full border p-3 rounded mb-3" required>
        <input name="phone" placeholder="Phone" class="w-full border p-3 rounded mb-3" required>
        <input name="address" placeholder="Full Address" class="w-full border p-3 rounded mb-3" required>
        <select name="city" class="w-full border p-3 rounded mb-3">
            <option value="dhaka">Inside Dhaka - 60TK</option>
            <option value="outside">Outside Dhaka - 120TK</option>
        </select>
        <button class="w-full bg-[#6a9732] text-white py-3 rounded-full">Confirm Order - Cash on Delivery</button>
    </form>
    <div class="bg-white p-6 rounded-xl shadow h-fit">
        <h3 class="font-bold text-xl mb-4">Order Summary</h3>
        @php $subtotal = $carts->sum(fn($c) => ($c->product->discount_price ?? $c->product->price) * $c->quantity) @endphp
        <p>Subtotal: ৳{{ $subtotal }}</p>
        <p class="font-bold text-lg mt-2">Total Payable: ৳{{ $subtotal }} + Delivery</p>
    </div>
</div>
@endsection
