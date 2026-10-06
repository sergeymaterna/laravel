@csrf

<div class="mb-4">
	<label for="title" class="block font-medium mb-1">Заголовок</label>
	<input type="text"
		name="title"
		id="title"
		value="{{ old('title', $post->title ?? '') }}"
		class="w-full border-gray-300 border rounded-lg p-2 focus:ring focus:ring-blue-200 @error('title') border-red-500 @enderror"
		required>
	@error('title')
	<p class="text-red-500 text-sm mt-1">{{ $message }}</p>
	@enderror
</div>

<div class="mb-4">
	<label for="content" class="block font-medium mb-1">Контент</label>
	<textarea name="content"
		id="content"
		rows="5"
		class="w-full border-gray-300 border rounded-lg p-2 focus:ring focus:ring-blue-200 @error('content') border-red-500 @enderror"
		required>{{ old('content', $post->content ?? '') }}</textarea>
	@error('content')
	<p class="text-red-500 text-sm mt-1">{{ $message }}</p>
	@enderror
</div>

<div class="mb-6 flex items-center">
	<input type="checkbox"
		name="is_published"
		id="is_published"
		value="1"
		{{ old('is_published', $post->is_published ?? false) ? 'checked' : '' }}
	class="mr-2">
	<label for="is_published" class="font-medium">Опублікувати одразу</label>
</div>

<div class="flex items-center space-x-3">
	<button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
		Зберегти
	</button>
	<a href="{{ route('posts.index') }}" class="text-gray-600 hover:underline">Скасувати</a>
</div>