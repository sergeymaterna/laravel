<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
	/**
	 * Перетворення ресурсу в масив для JSON
	 */
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'title' => $this->title,
			'slug' => $this->slug,
			'content' => $this->content,
			'excerpt' => $this->excerpt,
			'is_published' => $this->is_published,
			// Форматуємо дату для клієнта
			'created_at' => $this->created_at?->toIso8601String(),
			// Включаємо автора, ТІЛЬКИ якщо зв'язок завантажений (Eager Loading)
			'author' => new UserResource($this->whenLoaded('author')),
		];
	}
}
