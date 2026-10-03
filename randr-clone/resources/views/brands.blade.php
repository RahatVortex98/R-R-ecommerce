@extends('layouts.shop')
@section('title', 'All Brands')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-8"><h1 class="text-2xl font-bold mb-4">All Brands</h1>
  <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">@foreach($brands as $b)
    <a href="{{ route('brand', $b) }}" class="bg-white border rounded-xl p-4 text-center hover:shadow-md"><img src="{{ $b->image_url }}" class="h-20 mx-auto object-contain"><p class="mt-2 text-sm">{{ $b->name }}</p></a>@endforeach</div></div>
@endsection
