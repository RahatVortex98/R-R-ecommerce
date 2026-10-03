@extends('admin.layout')
@section('content')
<h1 class="text-xl font-semibold mb-4">Dashboard</h1>
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">@foreach($stats as $k => $v)<div class="bg-white rounded-lg shadow p-4"><p class="text-xs text-gray-500">{{ $k }}</p><p class="text-2xl font-bold">{{ $v }}</p></div>@endforeach</div>
<h2 class="font-semibold mb-2">Latest orders</h2>
@include('admin.orders.table', ['orders' => $latest])
@endsection
