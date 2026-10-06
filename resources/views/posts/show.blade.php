@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="mb-6">
	<a href="{{ route('posts.index') }}" class="text-blue-600 hover:underline">&larr; Назад до списку</a>
</div>

<article>
	<h1 class="text-3xl font-bold mb-2">{{ $post->title }}</h1>
	<div class="text-sm text-gray-500 mb-6">
		Створено: {{ $post->created_at->format('d.m.Y H:i') }} |
		Статус: {{ $post->is_published ? 'Опубліковано' : 'Чернетка' }}
	</div>

	<div class="prose max-w-none mb-6">
		{!! nl2br(e($post->content)) !!}
	</div>
</article>

<div class="flex space-x-3 border-t pt-4">
	<a href="{{ route('posts.edit', $post) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
		Редагувати
	</a>

	<form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Видалити цей пост?');">
		@csrf
		@method('DELETE')
		<button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">
			Видалити
		</button>
	</form>
</div>
@endsection