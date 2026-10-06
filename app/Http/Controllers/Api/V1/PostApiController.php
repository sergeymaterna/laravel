<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PostApiController extends Controller
{

	public function index()
	{
		$posts = Post::with('author')->latest()->paginate(10);

		// Автоматично обгортає колекцію в PostResource та додає пагінацію в JSON
		return PostResource::collection($posts);
	}

	public function store(StorePostRequest $request)
	{
		$post = Post::create($request->validated());

		// Повертаємо 201 Created та трансформований об'єкт
		return new PostResource($post);
	}

	public function show(Post $post)
	{
		return new PostResource($post->load('author'));
	}
	// GET /api/v1/posts
	public function index2(): JsonResponse
	{
		$posts = Post::latest()->paginate(10);

		return response()->json([
			'data' => $posts
		], 200);
	}

	// POST /api/v1/posts
	public function store2(Request $request): JsonResponse
	{
		$validated = $request->validate([
			'title' => 'required|string|max:255',
			'content' => 'required|string',
		]);


		$validated['user_id'] = 1;
		$post = Post::create($validated);

		return response()->json([
			'message' => 'Post created successfully',
			'data' => $post
		], 201);
	}

	// GET /api/v1/posts/{post}
	public function show2(Post $post): JsonResponse
	{
		return response()->json([
			'data' => $post
		], 200);
	}

	// PUT/PATCH /api/v1/posts/{post}
	public function update2(Request $request, Post $post): JsonResponse
	{
		$validated = $request->validate([
			'title' => 'sometimes|string|max:255',
			'content' => 'sometimes|string',
		]);

		$post->update($validated);

		return response()->json([
			'message' => 'Post updated successfully',
			'data' => $post
		], 200);
	}

	// DELETE /api/v1/posts/{post}
	public function destroy(Post $post): JsonResponse
	{
		$post->delete();

		return response()->json([
			'message' => 'Post deleted successfully'
		], 200); // або 204 No Content
	}
}