@extends('layouts.shop')
@section('title', $title)
@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
  <p class="text-sm text-gray-500"><a href="{{ route('home') }}" class="hover:underline">Home</a> / {{ $title }}</p>
  <div class="flex flex-wrap justify-between items-center gap-3 my-4">
    <h1 class="text-2xl font-bold">{{ $title }} <span class="text-sm font-normal text-gray-500">({{ $products->total() }} items)</span></h1>
    <form method="GET"><input type="hidden" name="q" value="{{ request('q') }}">
      <select name="sort" onchange="this.form.submit()" class="border-gray-300 rounded-lg text-sm">
        @foreach(['latest' => 'Newest', 'popular' => 'Popularity', 'price_asc' => 'Price: Low to High', 'price_desc' => 'Price: High to Low'] as $k => $v)
          <option value="{{ $k }}" @selected(request('sort', 'latest') === $k)>{{ $v }}</option>@endforeach
      </select></form>
  </div>
  @if($category && $category->children->count())
  <div class="flex flex-wrap gap-2 mb-4">@foreach($category->children as $ch)<a href="{{ route('collection', $ch) }}" class="bg-white border rounded-full px-4 py-1 text-sm hover:bg-pink-50">{{ $ch->name }}</a>@endforeach</div>
  @endif
  @if($products->count())
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">@include('partials.product-cards')</div>
    <div class="mt-6">{{ $products->links() }}</div>
  @else<p class="text-center text-gray-500 py-20">No products found.</p>@endif
</div>
@endsection
