@extends('admin.layout')
@section('content')
<div class="flex justify-between mb-4"><h1 class="text-xl font-semibold">{{ $cfg['title'] }}</h1>
  <a href="{{ route('admin.crud.create', $resource) }}" class="bg-pink-600 text-white px-4 py-2 rounded">+ New</a></div>
<table class="w-full bg-white rounded-lg shadow text-sm">
  <thead class="bg-gray-50 text-left"><tr><th class="p-3"></th>@foreach($cfg['cols'] as $c)<th class="capitalize">{{ str_replace('_', ' ', $c) }}</th>@endforeach<th></th></tr></thead>
  <tbody>@foreach($rows as $row)
    <tr class="border-t"><td class="p-2">@if(isset($row->image_url))<img src="{{ $row->image_url }}" class="w-10 h-10 rounded object-cover">@endif</td>
      @foreach($cfg['cols'] as $c)@php $v = $row->{$c}; @endphp
        <td>{{ is_bool($v) ? ($v ? '✓' : '—') : \Illuminate\Support\Str::limit((string) $v, 60) }}</td>@endforeach
      <td class="text-right pr-3 whitespace-nowrap"><a class="text-blue-600 mr-3" href="{{ route('admin.crud.edit', [$resource, $row->id]) }}">Edit</a>
        <form method="POST" action="{{ route('admin.crud.destroy', [$resource, $row->id]) }}" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-red-600">Delete</button></form></td></tr>
  @endforeach</tbody></table>
<div class="mt-4">{{ $rows->links() }}</div>
@endsection
