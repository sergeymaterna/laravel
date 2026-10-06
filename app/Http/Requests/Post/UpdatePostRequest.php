<?php

namespace App\Http\Requests\Post;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, ValidationRule|array<mixed>|string>
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
