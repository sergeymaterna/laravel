<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;


class AuthApiController extends Controller
{
	// POST /api/v1/login

	public function login(Request $request)
	{
		$request->validate([
			'email' => ['required', 'email'],
			'password' => ['required', 'string'],
			'two_factor_code' => ['nullable', 'digits:6'],
		]);

		$user = User::where('email', $request->email)->first();
		$user->tokens()->delete();

		if (! $user || ! Hash::check($request->password, $user->password)) {
			throw ValidationException::withMessages([
				'email' => ['Надані дані для входу некоректні.'],
			]);
		}

		// ЯКЩО У ЮЗЕРА УВІМКНЕНО 2FA:
		if ($user->two_factor_enabled) {

			// Перевіряємо, чи передано 2FA код
			if (! $request->filled('two_factor_code')) {
				return response()->json([
					'two_factor_required' => true,
					'message' => 'Будь ласка, введіть 6-значний код із Microsoft Authenticator.',
				], 403);
			}

			// Перевіряємо сам 2FA код
			$google2fa = new Google2FA();
			$valid = $google2fa->verifyKey($user->two_factor_secret, $request->two_factor_code);

			if (! $valid) {
				return response()->json([
					'message' => 'Недійсний код з Microsoft Authenticator.',
				], 422);
			}
		}

		// Якщо 2FA вимкнено АБО код з Microsoft Authenticator підтверджено — видаємо Sanctum токен!
		$token = $user->createToken('auth_token')->plainTextToken;

		return response()->json([
			'access_token' => $token,
			'token_type' => 'Bearer',
		]);
	}
	public function refresh(Request $request)
	{
		$user = $request->user();

		// 1. Видаляємо токен, з яким прийшов запит (ротація)
		$request->user()->currentAccessToken()->delete();

		// 2. Генеруємо новий токен
		$newToken = $user->createToken('auth_token')->plainTextToken;

		return response()->json([
			'access_token' => $newToken,
			'token_type' => 'Bearer',
		]);
	}

// GET /api/v1/2fa/setup (Захищено auth:sanctum)
	public function setupTwoFactor(Request $request)
	{
		$user = $request->user();
		$google2fa = new Google2FA();

		// 1. Генеруємо унікальний секрет, якщо його ще немає
		if (! $user->two_factor_secret) {
			$user->two_factor_secret = $google2fa->generateSecretKey();
			$user->save();
		}

		// 2. Формуємо URL для QR-коду, який зрозуміє Microsoft Authenticator
		$qrCodeUrl = $google2fa->getQRCodeUrl(
			config('app.name', 'Laravel API'),
			$user->email,
			$user->two_factor_secret
		);

		return response()->json([
			'message' => 'Відскануйте цей QR-код у додатку Microsoft Authenticator',
			'secret' => $user->two_factor_secret, // Можна ввести вручну в додатку
			'qr_code_url' => $qrCodeUrl,
		]);
	}
	public function enableTwoFactor(Request $request)
	{
		$request->validate([
			'code' => ['required', 'digits:6'],
		]);

		$user = $request->user();
		$google2fa = new Google2FA();

		// Перевіряємо введений 6-значний код проти збереженого секрету
		$valid = $google2fa->verifyKey($user->two_factor_secret, $request->code);

		if (! $valid) {
			return response()->json(['message' => 'Невірний код з Microsoft Authenticator'], 422);
		}

		// Вмикаємо 2FA для юзера
		$user->two_factor_enabled = true;
		$user->save();

		return response()->json(['message' => 'Двофакторна аутентифікація успішно увімкнена!']);
	}
	public function loginOld(Request $request)
	{
		$request->validate([
			'email' => 'required|email',
			'password' => 'required',
		]);

		$user = User::where('email', $request->email)->first();
//		dd(Hash::make('123456'));
//dd($request->password, $user->password, Hash::check($request->password, $user->password));

		if (! $user || ! Hash::check($request->password, $user->password)) {
			throw ValidationException::withMessages([
				'email' => ['Надані дані для входу некоректні.'],
			]);
		}

		// Генеруємо новий токен для юзера
		$token = $user->createToken('api-token')->plainTextToken;

		return response()->json([
			'token' => $token,
			'user' => $user,
		]);
	}
	// POST /api/v1/logout
	public function logout(Request $request)
	{
		// Видаляємо поточний токен
		$request->user()->currentAccessToken()->delete();

		return response()->json(['message' => 'Успішний вихід']);
	}
}