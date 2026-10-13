<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class AuthService
{
	public function login(array $credentials)
	{
		$response = Http::post(config('app.url') . '/api/v1/login', $credentials);

		if ($response->successful()) {
			return $response->json();
		}

		return null;
	}

	public function register(array $data)
	{
		$response = Http::post(config('app.url') . '/api/v1/register', $data);

		return $response->successful();
	}
}