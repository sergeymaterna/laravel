<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;
use Throwable;

class AuthApiController extends Controller
{
	public function __construct(protected AuthService $authService) {}

	/**
	 * @param Request $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function login(Request $request): JsonResponse
	{
		$validated = $request->validate([
			'email' => ['required', 'email'],
			'password' => ['required'],
			'two_factor_code' => ['nullable', 'digits:6'],
		]);

		$result = $this->authService->authenticateForApi($validated);

		if (isset($result['error'])) {
			return response()->json(['message' => $result['error']], $result['status'] ?? 400);
		}

		return response()->json([
			'access_token' => $result['token'],
			'token_type' => 'Bearer',
		]);
	}

	/**
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function refresh(Request $request): JsonResponse
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
//	public function setupTwoFactor(Request $request)
//	{
//		$data = $this->authService->setupTwoFactor($request->user());
//		return response()->json($data);
//	}
//
//	public function enableTwoFactor(Request $request)
//	{
//		$request->validate(['two_factor_code' => ['required', 'digits:6']]);
//
//		$success = $this->authService->enableTwoFactor($request->user(), $request->two_factor_code);
//
//		if (! $success) {
//			return response()->json(['message' => 'Невірний код 2FA.'], 422);
//		}
//
//		return response()->json(['message' => '2FA успішно увімкнено.']);
//	}

	/**
//	 * @param Request $request
//	 *
//	 * @return JsonResponse
//	 * @throws Throwable
	 */
//	public function setupTwoFactor2(Request $request): JsonResponse
//	{
//		$user = $request->user();
//		$google2fa = new Google2FA();
//
//		// 1. Генеруємо унікальний секрет, якщо його ще немає
//		if (! $user->two_factor_secret) {
//			$user->two_factor_secret = $google2fa->generateSecretKey();
//			$user->save();
//		}
//
//		// 2. Формуємо URL для QR-коду, який зрозуміє Microsoft Authenticator
//		$qrCodeUrl = $google2fa->getQRCodeUrl(
//			config('app.name', 'Laravel API'),
//			$user->email,
//			$user->two_factor_secret
//		);
//
//		return response()->json([
//			'message' => 'Відскануйте цей QR-код у додатку Microsoft Authenticator',
//			'secret' => $user->two_factor_secret, // Можна ввести вручну в додатку
//			'qr_code_url' => $qrCodeUrl,
//		]);
//	}

	/**
//	 * @param Request $request
//	 *
//	 * @return JsonResponse
//	 * @throws Throwable
	 */
//	public function enableTwoFactor2(Request $request): JsonResponse
//	{
//		$request->validate([
//			'code' => ['required', 'digits:6'],
//		]);
//
//		$user = $request->user();
//		$google2fa = new Google2FA();
//
//		// Перевіряємо введений 6-значний код проти збереженого секрету
//		$valid = $google2fa->verifyKey($user->two_factor_secret, $request->code);
//
//		if (! $valid) {
//			return response()->json(['message' => 'Невірний код з Microsoft Authenticator'], 422);
//		}
//
//		// Вмикаємо 2FA для юзера
//		$user->two_factor_enabled = true;
//		$user->save();
//
//		return response()->json(['message' => 'Двофакторна аутентифікація успішно увімкнена!']);
//	}

	/**
	 * @param Request $request
	 *
	 * @return JsonResponse
	 * @throws ValidationException
	 */
	public function loginOld(Request $request): JsonResponse
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

	/**
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function logout(Request $request): JsonResponse
	{
		// Видаляємо поточний токен
		$request->user()->currentAccessToken()->delete();

		return response()->json(['message' => 'Успішний вихід']);
	}
}