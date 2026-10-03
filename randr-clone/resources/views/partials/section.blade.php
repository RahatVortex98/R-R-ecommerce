@if($products->count())
<section class="max-w-7xl mx-auto px-4 mt-10">
  <div class="flex justify-between items-end mb-4">
    <h2 class="text-xl font-bold">{{ $title }}</h2>
    @isset($link)<a href="{{ $link }}" class="text-sm text-pink-600 hover:underline">View all items →</a>@endisset
  </div>
  <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">@include('partials.product-cards')</div>
</section>
@endif
