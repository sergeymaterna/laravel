<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;
use Throwable;
use Illuminate\Http\JsonResponse;

class AuthService
{
	protected Google2FA $google2fa;

	public function __construct()
	{
		$this->google2fa = new Google2FA();
	}

	/**
	 * Спільна бізнес-логіка перевірки доступу (Пороль + 2FA)
	 */
	public function validateCredentials(array $credentials): User|array
	{
		$user = User::where('email', $credentials['email'])->first();

		if (!$user || !Hash::check($credentials['password'], $user->password)) {
			return ['error' => 'Невірний email або пароль.', 'status' => 401];
		}

		if ($user->two_factor_enabled) {
			if (empty($credentials['two_factor_code'])) {
				return ['error' => 'Потрібен код двофакторної аутентифікації (2FA).', 'status' => 403];
			}

			$valid = $this->google2fa->verifyKey($user->two_factor_secret, $credentials['two_factor_code']);

			if (! $valid) {
				return ['error' => 'Невірний код 2FA.', 'status' => 422];
			}
		}

		return $user;
	}

	public function authenticateForApi(array $credentials): array
	{
		$result = $this->validateCredentials($credentials);
		if (is_array($result)) return $result;

		/** @var User $user */
		$user = $result;
		$user->tokens()->delete();
		$token = $user->createToken('api_token')->plainTextToken;

		return ['token' => $token];
	}

	public function authenticateForWeb(array $credentials): true|array
	{
		$result = $this->validateCredentials($credentials);
		if (is_array($result)) return $result;

		$user = $result;
		Auth::login($user);

		return true;
	}

	/**
	 * @param $credentials
	 *
	 * @return array
	 * @throws Throwable
	 */
	public function generateRegistrationSecret($credentials): array
	{
		$secret = $this->google2fa->generateSecretKey();

		$qrCodeUrl = $this->google2fa->getQRCodeUrl(
			config('app.name'),
			$credentials['email'],
			$secret
		);

		return [
			'secret' => $secret,
			'qrCodeUrl' => $qrCodeUrl,
		];
	}

	/**
	 * @param array $data
	 *
	 * @return User|array
	 * @throws Throwable
	 */
	public function registerWithTwoFactor(array $data): User|array
	{
		// Перевіряємо валідність введеного коду проти переданого секрету
		$valid = $this->google2fa->verifyKey($data['two_factor_secret'], $data['two_factor_code']);

		if (! $valid) {
			return ['error' => 'Невірний код двофакторної аутентифікації.', 'status' => 422];
		}

		return User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => Hash::make($data['password']),
			'role' => 'User',
			'two_factor_secret' => $data['two_factor_secret'],
			'two_factor_enabled' => true, // Одразу увімкнено!
		]);
	}
//	public function register(array $data): User
//	{
//		// При реєстрації одразу генеруємо 2FA секрет, але ще не вмикаємо,
//		// поки користувач не підтвердить його кодом (якщо це вимагається)
//		$secret = $this->google2fa->generateSecretKey();
//
//		return User::create([
//			'name' => $data['name'],
//			'email' => $data['email'],
//			'password' => Hash::make($data['password']),
//			'role' => 'User',
//			'two_factor_secret' => $secret,
//			'two_factor_enabled' => false, // Увімкнемо після підтвердження на кроці setup/enable
//		]);
//	}

	/**
	 * Генерує дані для налаштування 2FA (QR-код / секрет)
	 */
//	public function setupTwoFactor(User $user): array
//	{
//		if (empty($user->two_factor_secret)) {
//			$user->update(['two_factor_secret' => $this->google2fa->generateSecretKey()]);
//		}
//
//		$qrCodeUrl = $this->google2fa->getQRCodeUrl(
//			config('app.name'),
//			$user->email,
//			$user->two_factor_secret
//		);
//
//		return [
//			'secret' => $user->two_factor_secret,
//			'qr_code_url' => $qrCodeUrl,
//		];
//	}
	/**
	 * Підтверджує та остаточно активує 2FA
	 */
//	public function enableTwoFactor(User $user, string $code): bool
//	{
//		$valid = $this->google2fa->verifyKey($user->two_factor_secret, $code);
//
//		if ($valid) {
//			$user->update(['two_factor_enabled' => true]);
//			return true;
//		}
//
//		return false;
//	}
//	public function register(array $data)
//	{
//		$response = Http::post(config('app.url') . '/api/v1/register', $data);
//
//		return $response->successful();
//	}
}