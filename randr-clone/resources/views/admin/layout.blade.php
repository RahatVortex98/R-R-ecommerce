<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}"><title>Admin</title>@vite(['resources/css/app.css', 'resources/js/app.js'])</head>
<body class="bg-gray-100 text-gray-800">
<div class="flex min-h-screen">
  <aside class="w-56 bg-gray-900 text-gray-200 p-4 space-y-1 text-sm shrink-0">
    <p class="text-white font-bold text-lg mb-3">Admin</p>
    <a class="block px-3 py-2 rounded hover:bg-gray-700" href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a class="block px-3 py-2 rounded hover:bg-gray-700" href="{{ route('admin.orders.index') }}">Orders</a>
    @foreach(['products', 'categories', 'brands', 'banners', 'testimonials', 'pages', 'coupons'] as $r)
      <a class="block px-3 py-2 rounded hover:bg-gray-700 capitalize" href="{{ route('admin.crud.index', $r) }}">{{ $r }}</a>@endforeach
    <a class="block px-3 py-2 rounded hover:bg-gray-700" href="{{ route('admin.settings') }}">Settings</a>
    <hr class="border-gray-700 my-3"><a class="block px-3 py-2" href="{{ route('home') }}" target="_blank">View site ↗</a>
    <form method="POST" action="{{ route('logout') }}">@csrf<button class="px-3 py-2">Logout</button></form>
  </aside>
  <main class="flex-1 p-6 overflow-x-auto">
    @if(session('ok'))<div class="bg-green-100 text-pink-700 px-4 py-2 rounded mb-4">{{ session('ok') }}</div>@endif
    @if(session('error'))<div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">{{ session('error') }}</div>@endif
    @yield('content')
  </main>
</div></body></html>
