@extends('admin.layout')
@section('content')
<h1 class="text-xl font-semibold mb-4">{{ $model ? 'Edit' : 'New' }} — {{ $cfg['title'] }}</h1>
<form method="POST" enctype="multipart/form-data"
  action="{{ $model ? route('admin.crud.update', [$resource, $model->id]) : route('admin.crud.store', $resource) }}"
  class="bg-white rounded-lg shadow p-6 max-w-2xl space-y-4">@csrf @if($model) @method('PUT') @endif
  @foreach($cfg['fields'] as $name => $f)
    @php $val = old($name, $model ? $model->$name : $f['default']); @endphp
    <div>
      <label class="block text-sm font-medium mb-1">{{ \Illuminate\Support\Str::headline($name) }}</label>
      @switch($f['type'])
        @case('textarea')<textarea name="{{ $name }}" rows="6" class="w-full border-gray-300 rounded">{{ $val }}</textarea>@break
        @case('select')
          <select name="{{ $name }}" class="w-full border-gray-300 rounded"><option value="">— none —</option>
            @foreach(value($f['options']) as $k => $label)<option value="{{ $k }}" @selected((string) $val === (string) $k)>{{ $label }}</option>@endforeach</select>@break
        @case('check')<input type="checkbox" name="{{ $name }}" value="1" @checked($val) class="rounded">@break
        @case('image')
          @if($model && $model->$name)<img src="{{ \App\Support\Media::url($model->$name) }}" class="w-24 h-24 object-cover rounded mb-2">@endif
          <input type="file" name="{{ $name }}" accept="image/*">@break
        @case('datetime')<input type="datetime-local" name="{{ $name }}" value="{{ $val ? \Carbon\Carbon::parse($val)->format('Y-m-d\TH:i') : '' }}" class="w-full border-gray-300 rounded">@break
        @case('number')<input type="number" name="{{ $name }}" value="{{ $val }}" class="w-full border-gray-300 rounded">@break
        @default<input type="text" name="{{ $name }}" value="{{ $val }}" class="w-full border-gray-300 rounded">
      @endswitch
      @error($name)<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
    </div>
  @endforeach
  <div class="flex gap-3"><button class="bg-pink-600 text-white px-5 py-2 rounded">Save</button><a href="{{ route('admin.crud.index', $resource) }}" class="px-5 py-2 text-gray-600">Cancel</a></div>
@if($resource === 'products' && $model)
<hr class="my-2">
<div>
  <h3 class="font-semibold mb-2">Variants (shade / size)</h3>
  <table class="w-full text-sm mb-3">
    <thead class="text-left text-gray-500"><tr><th>Name</th><th>Price</th><th>Sale</th><th>Stock</th><th></th></tr></thead>
    @foreach($model->variants as $v)
    <form method="POST" action="{{ route('admin.variants.update', $v) }}">@csrf @method('PUT')
      <tr class="border-t">
        <td><input name="name" value="{{ $v->name }}" class="border-gray-300 rounded w-full text-sm"></td>
        <td><input name="price" type="number" value="{{ $v->price }}" placeholder="{{ $model->price }}" class="border-gray-300 rounded w-24 text-sm"></td>
        <td><input name="sale_price" type="number" value="{{ $v->sale_price }}" class="border-gray-300 rounded w-24 text-sm"></td>
        <td><input name="stock" type="number" value="{{ $v->stock }}" class="border-gray-300 rounded w-20 text-sm"></td>
        <td class="whitespace-nowrap"><button class="text-blue-600 text-xs mr-2">Save</button></td>
      </tr>
    </form>
    <form method="POST" action="{{ route('admin.variants.destroy', $v) }}" onsubmit="return confirm('Delete variant?')" class="inline">@csrf @method('DELETE')
      <input type="hidden"><button class="text-red-600 text-xs">Delete</button>
    </form>
    @endforeach
  </table>
  <form method="POST" action="{{ route('admin.variants.store', $model) }}" class="grid grid-cols-5 gap-2">@csrf
    <input name="name" placeholder="e.g. 30ml or Rose Pink" required class="border-gray-300 rounded text-sm col-span-2">
    <input name="price" type="number" placeholder="Price (opt.)" class="border-gray-300 rounded text-sm">
    <input name="stock" type="number" placeholder="Stock" required class="border-gray-300 rounded text-sm">
    <button class="bg-gray-800 text-white rounded text-sm">+ Add variant</button>
  </form>
</div>
@endif



</form>
@endsection
