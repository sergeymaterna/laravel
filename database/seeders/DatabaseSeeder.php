<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
	public function run(): void
	{
		User::factory()
			->state([
				'role' => 'User',
				'email' => 'matepna@gmail.com',
				'password' => Hash::make('123456'),
			])
			->create();

		User::factory(5)
			->state([
				'role' => 'User',
				'password' => Hash::make('123456')
			])
			->has(
				Post::factory(3)->state(fn () => [
					'title' => fake()->unique()->sentence(3),
					'content' => fake()->paragraph(),
					'slug' => fake()->unique()->slug()
				])
			)
			->create();
	}
}
