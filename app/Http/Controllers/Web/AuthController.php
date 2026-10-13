<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
	public function __construct(protected AuthService $authService) {}

	public function showLoginForm()
	{
		return view('auth.login');
	}

	public function login(Request $request)
	{
		$credentials = $request->validate([
			'email' => ['required', 'email'],
			'password' => ['required'],
			'two_factor_code' => ['nullable', 'digits:6'],
		]);

		$result = $this->authService->login($credentials);

		if (! $result) {
			return back()->withErrors(['email' => 'Невірні дані або потрібен код 2FA.'])->withInput();
		}

		// Якщо сервер повернув запит на 2FA
		if (isset($result['two_factor_required'])) {
			return back()->withErrors(['two_factor_code' => $result['message']])->withInput();
		}

		// Зберігаємо токен у сесію для подальших запитів
		session(['bearer_token' => $result['access_token']]);

		return redirect()->route('users.index');
	}

	public function showRegisterForm()
	{
		return view('auth.register');
	}

	public function register(Request $request)
	{
		$data = $request->validate([
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'password' => ['required', 'string', 'min:8', 'confirmed'],
		]);

		$success = $this->authService->register($data);

		if (! $success) {
			return back()->withErrors(['email' => 'Помилка реєстрації.'])->withInput();
		}

		return redirect()->route('login')->with('success', 'Реєстрація успішна! Увійдіть у систему.');
	}

	public function logout(Request $request)
	{
		session()->forget('bearer_token');
		$request->session()->invalidate();
		$request->session()->regenerateToken();

		return redirect()->route('login');
	}
}