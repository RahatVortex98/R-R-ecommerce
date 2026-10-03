@extends('layouts.shop')
@section('content')
<section class="max-w-7xl mx-auto px-4 mt-4 grid gap-4 {{ $promo->count() ? 'lg:grid-cols-3' : '' }}">
  <div @class(['relative overflow-hidden rounded-2xl', 'lg:col-span-2' => $promo->count()])
       x-data="{i:0,n:{{ max($hero->count(), 1) }}}" x-init="setInterval(()=>i=(i+1)%n,4500)">
    @forelse($hero as $k => $b)
      <a href="{{ $b->link ?: '#' }}" x-show="i==={{ $k }}" class="block"><img src="{{ $b->image_url }}" class="w-full aspect-[2.5/1] object-cover"></a>
    @empty
      <div class="aspect-[2.5/1] bg-gradient-to-r from-pink-600 to-fuchsia-500 flex items-center justify-center text-white text-2xl md:text-4xl font-extrabold">Authentic Beauty, Imported</div>
    @endforelse
    @if($hero->count() > 1)
    <div class="absolute bottom-3 inset-x-0 flex justify-center gap-2">
      @foreach($hero as $k => $b)<button @click="i={{ $k }}" :class="i==={{ $k }} ? 'bg-white' : 'bg-white/50'" class="w-2.5 h-2.5 rounded-full"></button>@endforeach
    </div>@endif
  </div>
  @if($promo->count())
  <div class="hidden lg:grid gap-4">@foreach($promo->take(2) as $b)<a href="{{ $b->link ?: '#' }}"><img src="{{ $b->image_url }}" class="rounded-2xl w-full h-full object-cover"></a>@endforeach</div>
  @endif
</section>

@if($flash->count())
<section class="max-w-7xl mx-auto px-4 mt-10">
  <div class="flex justify-between items-end mb-4">
    <h2 class="text-xl font-bold text-red-600">⚡ Flash Sale
      <span class="ml-2 text-sm bg-red-600 text-white px-2 py-0.5 rounded" x-data="{t:'',end:new Date('{{ $flashEnds->toIso8601String() }}')}"
        x-init="setInterval(()=>{let d=Math.max(0,end-new Date());t=[Math.floor(d/36e5),Math.floor(d%36e5/6e4),Math.floor(d%6e4/1e3)].map(x=>String(x).padStart(2,'0')).join(':')},1000)" x-text="t"></span></h2>
    <a href="{{ route('flash') }}" class="text-sm text-pink-600 hover:underline">View all →</a>
  </div>
  <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">@include('partials.product-cards', ['products' => $flash])</div>
</section>
@endif

@if($featured->count())
<section class="max-w-7xl mx-auto px-4 mt-10">
  <h2 class="text-xl font-bold mb-4">Featured Categories</h2>
  <div class="grid grid-cols-3 md:grid-cols-5 lg:grid-cols-10 gap-3">
    @foreach($featured as $c)
    <a href="{{ route('collection', $c) }}" class="bg-white border rounded-xl p-3 text-center hover:shadow-md">
      <img src="{{ $c->image_url }}" class="w-14 h-14 mx-auto rounded-full object-cover"><p class="text-xs mt-2 font-medium">{{ $c->name }}</p>
    </a>
    @endforeach
  </div>
</section>
@endif

@include('partials.section', ['title' => 'Top Selling Products', 'products' => $top, 'link' => route('shop', ['sort' => 'popular'])])

@if($brands->count())
<section class="max-w-7xl mx-auto px-4 mt-10">
  <div class="flex justify-between mb-4"><h2 class="text-xl font-bold">Our Brands</h2><a href="{{ route('brands') }}" class="text-sm text-pink-600">See all</a></div>
  <div class="flex gap-4 overflow-x-auto pb-2">
    @foreach($brands as $b)<a href="{{ route('brand', $b) }}" class="shrink-0 bg-white border rounded-xl p-3 w-32 text-center"><img src="{{ $b->image_url }}" class="h-16 mx-auto object-contain"></a>@endforeach
  </div>
</section>
@endif

@foreach($sections as $sec)
  @include('partials.section', ['title' => $sec['cat']->name, 'products' => $sec['products'], 'link' => route('collection', $sec['cat'])])
@endforeach

@include('partials.section', ['title' => '100% Authentic', 'products' => $certified, 'link' => route('shop')])

<section class="max-w-7xl mx-auto px-4 mt-10"
  x-data="{next:'{{ $more->nextPageUrl() }}', busy:false, async load(){ this.busy=true; const r=await fetch(this.next,{headers:{Accept:'application/json'}}); const d=await r.json(); this.\$refs.grid.insertAdjacentHTML('beforeend', d.html); this.next=d.next; this.busy=false; }}">
  <div class="flex justify-between mb-4"><h2 class="text-xl font-bold">Just For You</h2><a href="{{ route('shop') }}" class="text-sm text-pink-600">View All Products</a></div>
  <div x-ref="grid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">@include('partials.product-cards', ['products' => $more])</div>
  <div class="text-center mt-6" x-show="next"><button @click="load()" :disabled="busy" class="border border-pink-600 text-pink-600 px-8 py-2 rounded-lg hover:bg-pink-50" x-text="busy ? 'Loading...' : 'Load More'"></button></div>
</section>

@if($testimonials->count())
<section class="max-w-7xl mx-auto px-4 mt-12">
  <h2 class="text-xl font-bold mb-4 text-center">What Our Customers Say</h2>
  <div class="grid md:grid-cols-3 gap-4">
    @foreach($testimonials as $t)
    <div class="bg-white border rounded-xl p-5"><p class="text-sm text-gray-600">“{{ $t->body }}”</p>
      <div class="flex items-center gap-3 mt-4"><img src="{{ $t->image_url }}" class="w-10 h-10 rounded-full object-cover"><div><p class="font-semibold text-sm">{{ $t->name }}</p><p class="text-xs text-gray-500">{{ $t->role }}</p></div></div>
    </div>
    @endforeach
  </div>
</section>
@endif
@endsection
