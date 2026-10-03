@php $s = fn ($k, $d = null) => \App\Models\Setting::get($k, $d); @endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', $s('site_name', 'My Shop'))</title>
<meta name="description" content="@yield('description', $s('tagline'))">
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800" x-data x-init="$store.cart.count = {{ $cartCount }}">
@if($s('announcement'))<div class="bg-pink-600 text-white text-center text-sm py-1.5">{{ $s('announcement') }}</div>@endif

<header class="bg-white shadow-sm sticky top-0 z-40" x-data="{menu:false}">
  <div class="max-w-7xl mx-auto px-4 h-16 flex items-center gap-4">
    <button class="lg:hidden text-2xl" @click="menu=true">☰</button>
    <a href="{{ route('home') }}" class="text-2xl font-extrabold text-pink-600">{{ $s('site_name', 'My Shop') }}</a>
    <form action="{{ route('shop') }}" class="flex-1 hidden md:flex max-w-xl">
      <input name="q" value="{{ request('q') }}" placeholder="Search products..." class="w-full border-gray-300 rounded-l-lg px-4 py-2 focus:ring-pink-600 focus:border-pink-600">
      <button class="bg-pink-600 text-white px-5 rounded-r-lg">Search</button>
    </form>
    <nav class="ml-auto flex items-center gap-4 text-sm">
      <a href="{{ route('track') }}" class="hidden md:inline hover:text-pink-600">Track Order</a>
      @auth
        <a href="{{ route('account.orders') }}" class="hover:text-pink-600">My Account</a>
        @if(auth()->user()->is_admin)<a href="{{ route('admin.dashboard') }}" class="text-blue-600">Admin</a>@endif
        <form method="POST" action="{{ route('logout') }}" class="hidden md:inline">@csrf<button class="hover:text-pink-600">Logout</button></form>
      @else
        <a href="{{ route('login') }}" class="hover:text-pink-600">Sign In</a>
      @endauth
      <a href="{{ route('wishlist') }}" class="hover:text-pink-600">♡<span class="hidden md:inline"> Wishlist</span></a>
      <button @click="$store.cart.open=true; $store.cart.load()" class="bg-pink-600 text-white px-3 py-2 rounded-lg">
        🛒 Cart <span x-text="$store.cart.count" class="bg-yellow-400 text-black text-xs rounded-full px-1.5 ml-1"></span>
      </button>
    </nav>
  </div>
  <div class="hidden lg:block border-t">
    <ul class="max-w-7xl mx-auto px-4 flex gap-6 text-sm font-medium">
      <li class="py-3"><a href="{{ route('offer') }}" class="text-orange-600 hover:underline">Offer Zone</a></li>
      @foreach($menu as $c)
      <li class="relative group py-3">
        <a href="{{ route('collection', $c) }}" class="hover:text-pink-600">{{ $c->name }}@if($c->children->count()) ▾@endif</a>
        @if($c->children->count())
        <div class="absolute left-0 top-full hidden group-hover:block bg-white border shadow-lg rounded-lg min-w-52 py-2 z-50">
          @foreach($c->children as $ch)<a href="{{ route('collection', $ch) }}" class="block px-4 py-1.5 hover:bg-pink-50 hover:text-pink-600">{{ $ch->name }}</a>@endforeach
        </div>
        @endif
      </li>
      @endforeach
      <li class="py-3"><a href="{{ route('flash') }}" class="text-red-600 hover:underline">⚡ Flash Sale</a></li>
    </ul>
  </div>

  {{-- mobile menu --}}
  <div x-show="menu" x-cloak class="fixed inset-0 z-50 lg:hidden">
    <div class="absolute inset-0 bg-black/40" @click="menu=false"></div>
    <div class="absolute left-0 top-0 h-full w-72 bg-white overflow-y-auto p-4">
      <form action="{{ route('shop') }}" class="mb-4 flex"><input name="q" placeholder="Search..." class="w-full border-gray-300 rounded-l px-3 py-2"><button class="bg-pink-600 text-white px-3 rounded-r">Go</button></form>
      <a href="{{ route('offer') }}" class="block py-2 text-orange-600 font-medium">Offer Zone</a>
      @foreach($menu as $c)
        <div x-data="{o:false}" class="border-t">
          <div class="flex justify-between py-2"><a href="{{ route('collection', $c) }}">{{ $c->name }}</a>@if($c->children->count())<button @click="o=!o">▾</button>@endif</div>
          <div x-show="o" class="pl-4 pb-2 space-y-1 text-sm">@foreach($c->children as $ch)<a href="{{ route('collection', $ch) }}" class="block">{{ $ch->name }}</a>@endforeach</div>
        </div>
      @endforeach
      <a href="{{ route('flash') }}" class="block py-2 border-t text-red-600">⚡ Flash Sale</a>
      <a href="{{ route('track') }}" class="block py-2 border-t">Track Order</a>
    </div>
  </div>
</header>

@if(session('ok'))<div class="max-w-7xl mx-auto px-4 mt-3"><div class="bg-green-100 text-pink-700 px-4 py-2 rounded">{{ session('ok') }}</div></div>@endif
@if(session('error'))<div class="max-w-7xl mx-auto px-4 mt-3"><div class="bg-red-100 text-red-800 px-4 py-2 rounded">{{ session('error') }}</div></div>@endif

<main>@yield('content')</main>

<footer class="bg-gray-900 text-gray-300 mt-16 pt-10 pb-24 lg:pb-8">
  <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-4 gap-8 text-sm">
    <div class="space-y-1">
      <h4 class="text-white font-bold text-lg">{{ $s('site_name') }}</h4>
      <p>{{ $s('tagline') }}</p><p>{{ $s('address') }}</p><p>{{ $s('phone') }}</p><p>{{ $s('email') }}</p>
      <div class="flex gap-3 pt-2">
        @if($s('facebook'))<a href="{{ $s('facebook') }}" target="_blank" class="hover:text-white">Facebook</a>@endif
        @if($s('instagram'))<a href="{{ $s('instagram') }}" target="_blank" class="hover:text-white">Instagram</a>@endif
      </div>
    </div>
    @foreach(['information' => 'Information', 'support' => 'Support', 'policy' => 'Consumer Policy'] as $g => $label)
    <div><h4 class="text-white font-semibold mb-2">{{ $label }}</h4>
      <ul class="space-y-1">@foreach($footerPages[$g] ?? [] as $pg)<li><a href="{{ route('page', $pg) }}" class="hover:text-white">{{ $pg->title }}</a></li>@endforeach</ul>
    </div>
    @endforeach
  </div>
  <p class="text-center text-xs mt-8">© {{ date('Y') }} {{ $s('site_name') }}. All rights reserved.</p>
</footer>

{{-- cart drawer --}}
<div x-show="$store.cart.open" x-cloak class="fixed inset-0 z-50">
  <div class="absolute inset-0 bg-black/40" @click="$store.cart.open=false"></div>
  <aside class="absolute right-0 top-0 h-full w-full max-w-sm bg-white shadow-xl flex flex-col">
    <div class="flex justify-between items-center p-4 border-b"><h3 class="font-semibold">Shopping Cart</h3><button @click="$store.cart.open=false" class="text-xl">✕</button></div>
    <div class="flex-1 overflow-y-auto" x-html="$store.cart.html"></div>
  </aside>
</div>

{{-- floating whatsapp + mobile bottom nav --}}
@if($s('whatsapp'))<a href="https://wa.me/{{ $s('whatsapp') }}" target="_blank" class="fixed right-4 bottom-20 lg:bottom-6 z-30 bg-pink-500 text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg text-xl">💬</a>@endif
<nav class="lg:hidden fixed bottom-0 inset-x-0 bg-white border-t z-30 grid grid-cols-5 text-center text-xs py-2">
  <a href="{{ route('home') }}">🏠<br>Home</a>
  <a href="{{ route('shop') }}">🛍<br>Shop</a>
  <button @click="$store.cart.open=true; $store.cart.load()">🛒<br>Cart (<span x-text="$store.cart.count"></span>)</button>
  <a href="{{ route('wishlist') }}">♡<br>Wishlist</a>
  <a href="{{ auth()->check() ? route('account.orders') : route('login') }}">👤<br>Account</a>
</nav>
</body>
</html>
