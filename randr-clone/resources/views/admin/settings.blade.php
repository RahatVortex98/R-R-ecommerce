@extends('admin.layout')
@section('content')
<h1 class="text-xl font-semibold mb-4">Settings</h1>
<form method="POST" action="{{ route('admin.settings.update') }}" class="bg-white rounded-lg shadow p-6 max-w-2xl space-y-4">@csrf
  @foreach($fields as $k => $label)<div><label class="block text-sm font-medium mb-1">{{ $label }}</label><input name="{{ $k }}" value="{{ \App\Models\Setting::get($k) }}" class="w-full border-gray-300 rounded"></div>@endforeach
  <button class="bg-pink-600 text-white px-5 py-2 rounded">Save</button></form>
@endsection
