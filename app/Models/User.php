<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
	// Traits — це "Behaviors" у стилі Laravel
	use HasFactory, Notifiable, SoftDeletes, HasApiTokens, HasFactory, Notifiable, SoftDeletes;

	/**
	 * Поля, дозволені для масового заповнення (Mass Assignment)
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
		'role',
		'is_active',
	];

	/**
	 * Поля, які приховуються при серіалізації в JSON (наприклад, для API)
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * Автоматичне приведення типів
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
		'password' => 'hashed', // Автоматично хешує пароль при збереженні (замість BeforeSave)
		'is_active' => 'boolean',
	];

	// ==========================================
	// RELATIONS (Зв'язки)
	// ==========================================

	/**
	 * Один користувач має багато постів (1:N)
	 */
	public function posts(): HasMany
	{
		return $this->hasMany(Post::class, 'user_id', 'id');
	}

	// ==========================================
	// LIFECYCLE HOOKS (Аналог BeforeSave / AfterSave)
	// ==========================================

	protected static function booted(): void
	{
		// Аналог BeforeSave: спрацьовує перед створенням запису
		static::creating(function (User $user) {
			if (empty($user->name)) {
				$user->name = 'User_' . Str::random(6);
			}
		});

		// Аналог AfterDelete: спрацьовує після м'якого видалення користувача
		static::deleted(function (User $user) {
			// Можна, наприклад, каскадно приховати всі його пости
			$user->posts()->update(['is_published' => false]);
		});
	}

	// ==========================================
	// SCOPES (Локальні фільтри для Eloquent)
	// ==========================================

	public function scopeActive($query)
	{
		return $query->where('is_active', true);
	}
}