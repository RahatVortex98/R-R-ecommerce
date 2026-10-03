@extends('admin.layout')
@section('content')
<h1 class="text-xl font-semibold mb-4">Orders</h1>
<form class="flex gap-2 mb-4"><input name="q" value="{{ request('q') }}" placeholder="Order no / phone / name" class="border-gray-300 rounded">
  <select name="status" class="border-gray-300 rounded"><option value="">All statuses</option>@foreach(\App\Models\Order::STATUSES as $s)<option @selected(request('status') === $s)>{{ $s }}</option>@endforeach</select>
  <button class="bg-gray-800 text-white px-4 rounded">Filter</button></form>
@include('admin.orders.table')
<div class="mt-4">{{ $orders->links() }}</div>
@endsection
