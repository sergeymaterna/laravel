<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Post extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'user_id',
		'title',
		'slug',
		'content',
		'excerpt',
		'cover_image',
		'is_published',
		'published_at',
	];

	protected $casts = [
		'is_published' => 'boolean',
		'published_at' => 'datetime',
	];

	// ==========================================
	// RELATIONS (Зв'язки)
	// ==========================================

	/**
	 * Пост належить автору (User) (N:1)
	 */
	public function author(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	// ==========================================
	// LIFECYCLE HOOKS (BeforeSave, BeforeUpdate)
	// ==========================================

	protected static function booted(): void
	{
		// Перед збереженням (creating / updating) генеруємо slug і excerpt
		static::saving(function (Post $post) {
			// Якщо slug не заповнений, генеруємо з title
			if (empty($post->slug)) {
				$post->slug = Str::slug($post->title);
			}

			// Якщо excerpt не вказано, беремо перші 150 символів з content
			if (empty($post->excerpt) && !empty($post->content)) {
				$post->excerpt = Str::limit(strip_tags($post->content), 150);
			}

			// Якщо позначено як опублікований, але дата публікації порожня
			if ($post->is_published && is_null($post->published_at)) {
				$post->published_at = now();
			}
		});
	}

	// ==========================================
	// SCOPES (Кастомні методи вибірки DB)
	// ==========================================

	/**
	 * Скоуп для отримання лише опублікованих постів: Post::published()->get()
	 */
	public function scopePublished($query)
	{
		return $query->where('is_published', true)
			->whereNotNull('published_at')
			->where('published_at', '<=', now());
	}
}