<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('posts', function (Blueprint $table) {
			$table->id();

			// Вторинний ключ (Foreign Key) з прив'язкою до таблиці users
			$table->foreignId('user_id')
				->constrained('users')
				->cascadeOnDelete(); // Якщо видалять юзера з бази, видаляться і пости

			$table->string('title');
			$table->string('slug')->unique(); // ЧПУ адреса (URL-friendly string)
			$table->text('content');
			$table->text('excerpt')->nullable(); // Короткий анонс
			$table->string('cover_image')->nullable();
			$table->boolean('is_published')->default(false);
			$table->timestamp('published_at')->nullable();

			$table->timestamps(); // створює created_at і updated_at
			$table->softDeletes(); // додає deleted_at для SoftDeletes
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('posts');
	}
};