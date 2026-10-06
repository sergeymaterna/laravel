@extends('layouts.app')

@section('title', 'Редагувати пост')

@section('content')
<h1 class="text-2xl font-bold mb-6">Редагування поста #{{ $post->id }}</h1>

<form action="{{ route('posts.update', $post) }}" method="POST">
	@method('PUT')
	@include('posts._form')
</form>
@endsection