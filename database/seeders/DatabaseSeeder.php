<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
	public function run(): void
	{
		// Твій код генерації тестових даних
		User::factory(15)
			->state(['role' => 'User'])
			->has(
				Post::factory(5)->state(fn () => [
					'title' => fake()->unique()->sentence(3),
					'content' => fake()->paragraph(),
					'slug' => fake()->unique()->slug()
				])
			)
			->create();
	}
}
