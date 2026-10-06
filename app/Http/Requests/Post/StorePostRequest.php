<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
	/**
	 * Чи має право користувач робити цей запит (Авторизація)
	 */
	public function authorize(): bool
	{
		return true; // Наразі дозволяємо всім, пізніше сюди підключимо Policies
	}

	/**
	 * Правила валідації
	 */
	public function rules(): array
	{
		return [
			'title' => ['required', 'string', 'max:255'],
			'content' => ['required', 'string'],
			'is_published' => ['sometimes', 'boolean'],
		];
	}

	/**
	 * Кастомні повідомлення про помилки (опціонально)
	 */
	public function messages(): array
	{
		return [
			'title.required' => 'Заголовок поста є обов\'язковим!',
            'content.required' => 'Текст поста не може бути порожнім.',
        ];
    }
}