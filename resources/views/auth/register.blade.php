@extends('layouts.app')

@section('content')
<div class="container max-w-md mx-auto mt-10">
	<h1 class="text-2xl font-bold mb-6">Реєстрація</h1>

	<form method="POST" action="{{ route('register') }}">
		@csrf
		<div class="mb-4">
			<label class="block text-gray-700">Ім'я</label>
			<input type="text" name="name" value="{{ old('name') }}" required class="w-full border p-2 rounded">
		</div>
		<div class="mb-4">
			<label class="block text-gray-700">Email</label>
			<input type="email" name="email" value="{{ old('email') }}" required class="w-full border p-2 rounded">
		</div>
		<div class="mb-4">
			<label class="block text-gray-700">Пароль</label>
			<input type="password" name="password" required class="w-full border p-2 rounded">
		</div>
		<div class="mb-4">
			<label class="block text-gray-700">Підтвердження пароля</label>
			<input type="password" name="password_confirmation" required class="w-full border p-2 rounded">
		</div>
		<button type="submit" class="w-full bg-green-600 text-white p-2 rounded">Зареєструватися</button>
	</form>
</div>
@endsection