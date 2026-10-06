@extends('layouts.app')

@section('title', 'Створити пост')

@section('content')
<h1 class="text-2xl font-bold mb-6">Створення нового поста</h1>

<form action="{{ route('posts.store') }}" method="POST">
	@include('posts._form')
</form>
@endsection