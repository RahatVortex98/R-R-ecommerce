@extends('layouts.shop')
@section('title', $page->title)
@section('content')
<div class="max-w-3xl mx-auto px-4 py-10"><h1 class="text-3xl font-bold mb-4">{{ $page->title }}</h1>
  <div class="bg-white border rounded-xl p-6 whitespace-pre-line leading-relaxed">{!! nl2br(e($page->body)) !!}</div></div>
@endsection
