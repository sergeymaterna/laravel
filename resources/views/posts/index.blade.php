@extends('layouts.app')

@section('title', 'Список постів')

@section('content')
<div class="flex justify-between items-center mb-6">
	<h1 class="text-2xl font-bold">Список постів</h1>
	<a href="{{ route('posts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
		+ Створити пост
	</a>
</div>

@if ($posts->isEmpty())
<p class="text-gray-500">Постів поки немає.</p>
@else
<div class="overflow-x-auto">
	<table class="w-full text-left border-collapse">
		<thead>
		<tr class="border-b bg-gray-50">
			<th class="p-3">ID</th>
			<th class="p-3">Заголовок</th>
			<th class="p-3">Статус</th>
			<th class="p-3 text-right">Дії</th>
		</tr>
		</thead>
		<tbody>
		@foreach ($posts as $post)
		<tr class="border-b hover:bg-gray-50">
			<td class="p-3">{{ $post->id }}</td>
			<td class="p-3 font-medium">{{ $post->title }}</td>
			<td class="p-3">
				@if($post->is_published)
				<span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Опубліковано</span>
				@else
				<span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">Чернетка</span>
				@endif
			</td>
			<td class="p-3 text-right space-x-2">
				<a href="{{ route('posts.show', $post) }}" class="text-gray-600 hover:text-gray-900">Перегляд</a>
				<a href="{{ route('posts.edit', $post) }}" class="text-blue-600 hover:text-blue-900">Редагувати</a>

				<form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline-block" onsubmit="return confirm('Вилити цей пост?');">
					@csrf
					@method('DELETE')
					<button type="submit" class="text-red-600 hover:text-red-900">Видалити</button>
				</form>
			</td>
		</tr>
		@endforeach
		</tbody>
	</table>
</div>

{{-- Пагінація --}}
<div class="mt-4">
	{{ $posts->links() }}
</div>
@endif
@endsection