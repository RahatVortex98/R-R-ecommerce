@extends('layouts.shop')
@section('title', 'My Orders')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-8"><h1 class="text-2xl font-bold mb-4">My Orders</h1>
  @forelse($orders as $o)
  <div class="bg-white border rounded-xl p-4 mb-3">
    <div class="flex justify-between"><b>{{ $o->order_no }}</b><span class="text-sm capitalize px-2 py-0.5 rounded bg-green-100 text-pink-700">{{ $o->status }}</span></div>
    <p class="text-sm text-gray-500">{{ $o->created_at->format('d M Y') }} · {{ $o->items->sum('qty') }} items · <b>৳{{ number_format($o->total) }}</b></p>
  </div>
  @empty<p class="text-gray-500 text-center py-16">No orders yet.</p>@endforelse
  {{ $orders->links() }}
  <p class="mt-6 text-sm"><a href="{{ route('profile.edit') }}" class="text-pink-600 underline">Edit profile</a></p>
</div>
@endsection
