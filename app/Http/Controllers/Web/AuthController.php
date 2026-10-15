<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;
use Throwable;

class AuthController extends Controller
{
	public function __construct(protected AuthService $authService) {}

	public function showLoginForm()
	{
		return view('auth.login');
	}

	/**
	 * @param Request $request
	 *
	 * @return RedirectResponse
	 */
	public function login(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'email' => ['required', 'email'],
			'password' => ['required'],
			'two_factor_code' => ['required', 'digits:6'],
		]);

		$result = $this->authService->authenticateForWeb($validated);

		if (isset($result['error'])) {
			return back()->withErrors(['email' => $result['error']])->withInput();
		}

		$request->session()->regenerate();

		return redirect()->intended(route('posts.index'));
	}

	public function showRegisterForm()
	{
		// Генеруємо секрет і передаємо дані для QR-коду на форму
		return view('auth.register', ['step' => 1]);
	}

	/**
	 * @param Request $request
	 *
	 * @return Factory|View|RedirectResponse
	 * @throws Throwable
	 */
	public function register(Request $request): mixed
	{

		$step = $request->input('step', 1);
		if (empty($step)) {
			throw new Exception("sadasd");
		}

		if ($step == 1 || $request->session()->has('registration_data')) {
			$validated = $request->validate([
				'name' => ['required', 'string', 'max:255'],
				'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
				'password' => ['required', 'string', 'min:8', 'confirmed'],
			]);

			$request->session()->put('registration_data', $validated);

			return view('auth.register', [
				'name' => session('registration_data.name'),
				'email' => session('registration_data.email'),
				'step' => 2,
			] +  $this->authService->generateRegistrationSecret($validated));
		}

		if ($step == 2 || $request->session()->has('registration_data')) {
			$validated = $request->validate([
				'name' => ['required', 'string', 'max:255'],
				'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
				'password' => ['required', 'string', 'min:8', 'confirmed'],
				'two_factor_secret' => ['required', 'string'],
				'two_factor_code' => ['required', 'digits:6'],
			]);

		}

		$result = $this->authService->registerWithTwoFactor($validated);

		if (is_array($result)) {
			return back()->withErrors(['two_factor_code' => $result['error']])->withInput();
		}

		// Автоматично логінимо після успішної реєстрації з 2FA
		auth()->login($result);

		return redirect()->route('users.index')->with('success', 'Акаунт успішно створено та захищено 2FA!');
	}

	public function logout(Request $request)
	{

		Auth::logout();
//		session()->forget('bearer_token');
//		$request->session()->invalidate();
//		$request->session()->regenerateToken();

		return redirect()->route('login');
	}
}