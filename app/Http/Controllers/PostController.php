<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;

class PostController extends Controller
{
	// READ (List) — Список усіх записів
	public function index()
	{
		$posts = Post::latest()->paginate(10);
		return view('posts.index', compact('posts'));
	}

	// CREATE (Form) — Форма створення
	public function create()
	{
		return view('posts.create');
	}

	// UPDATE (Form) — Форма редагування
	public function edit(Post $post)
	{
		return view('posts.edit', compact('post'));
	}

	public function store(StorePostRequest $request)
	{
		// $request->validated() повертає ТІЛЬКИ ті дані, які пройшли валідацію!
		Post::create($request->validated());

		return redirect()->route('posts.index')->with('success', 'Пост створено!');
	}

	public function update(UpdatePostRequest $request, Post $post)
	{
		$post->update($request->validated());

		return redirect()->route('posts.index')->with('success', 'Пост оновлено!');
	}

	// CREATE (Action) — Збереження нового запису
	public function store2(Request $request)
	{
		$validated = $request->validate([
			'title' => 'required|string|max:255',
			'content' => 'required|string',
		]);
		$validated['user_id'] = 1;

		Post::create($validated);

		return redirect()->route('posts.index')->with('success', 'Пост успішно створено!');
	}

	// READ (Single) — Перегляд одного запису
	public function show(Post $post) // Implicit Model Binding
	{
		return view('posts.show', compact('post'));
	}

	// UPDATE (Action) — Оновлення запису
	public function update2(Request $request, Post $post)
	{
		$validated = $request->validate([
			'title' => 'required|string|max:255',
			'content' => 'required|string',
		]);

		$post->update($validated);

		return redirect()->route('posts.index')->with('success', 'Пост оновлено!');
	}

	// DELETE — Видалення запису
	public function destroy(Post $post)
	{
		$post->delete();

		return redirect()->route('posts.index')->with('success', 'Пост видалено!');
	}
}
