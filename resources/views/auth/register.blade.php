@extends('layouts.app')

@section('content')
<div class="container max-w-md mx-auto mt-10 p-6 bg-white border rounded shadow">
	<h1 class="text-2xl font-bold mb-6">Реєстрація з 2FA</h1>

	@if($errors->any())
	<div class="bg-red-100 text-red-700 p-3 mb-4 rounded text-sm">{{ $errors->first() }}</div>
	@endif

	<form method="POST" action="{{ route('register') }}" autocomplete="on">
		@csrf

		<!-- Ховаємо секретний ключ, щоб відправити його на бекенд при сабміті -->
		<input type="hidden" name="step" value="{{ $step }}">
		@isset($secret)
			<input type="hidden" name="two_factor_secret" value="{{ $secret }}">
		@endif

		<div class="mb-4">
			<label class="block text-gray-700 text-sm font-bold mb-2">Ім'я</label>
			<input type="text" name="name" value="{{ session('registration_data.name') }}" required class="w-full border p-2 rounded">
		</div>

		<div class="mb-4">
			<label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
			<input type="email" name="email" value="{{ session('registration_data.email') }}" required class="w-full border p-2 rounded">
		</div>

		<div class="mb-4">
			<label class="block text-gray-700 text-sm font-bold mb-2">Пароль</label>
			<input type="password" name="password" required class="w-full border p-2 rounded" autocomplete="new-password">
		</div>

		<div class="mb-4">
			<label class="block text-gray-700 text-sm font-bold mb-2">Підтвердження пароля</label>
			<input type="password" name="password_confirmation" required class="w-full border p-2 rounded">
		</div>

		<!-- Блок виведення QR-коду 2FA -->
		@isset($secret)
			<div class="mb-6 p-4 bg-gray-50 border rounded text-center">
				<p class="text-sm font-semibold text-gray-700 mb-2">Відскануйте QR-код у Microsoft Authenticator:</p>

				<div class="inline-block p-2 bg-white border rounded">
					{!! QrCode::size(160)->generate($qrCodeUrl) !!}
				</div>

				<div class="mt-4">
					<label class="block text-gray-700 text-sm font-bold mb-2">Введіть 6-значний код з додатку:</label>
					<input type="text" name="two_factor_code" maxlength="6" required class="w-32 text-center border p-2 rounded tracking-widest text-lg">
				</div>
			</div>
		@endif

		<button type="submit" class="w-full bg-green-600 text-white p-2 rounded font-bold hover:bg-green-700">Зареєструватися</button>
	</form>
</div>
@endsection