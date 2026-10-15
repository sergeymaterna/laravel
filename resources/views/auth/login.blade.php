@extends('layouts.app')

@section('content')
<div class="container max-w-md mx-auto mt-10">
	<h1 class="text-2xl font-bold mb-6">Вхід до системи</h1>

	@if($errors->any())
	<div class="bg-red-100 text-red-700 p-3 mb-4 rounded">{{ $errors->first() }}</div>
	@endif
	<form method="POST" action="{{ route('login') }}">
		@csrf
		<div class="mb-4">
			<label class="block text-gray-700">Email</label>
			<input type="email" name="email" value="{{ old('email') }}" required class="w-full border p-2 rounded">
		</div>
		<div class="mb-4">
			<label class="block text-gray-700">Пароль</label>
			<input type="password" name="password" required class="w-full border p-2 rounded">
		</div>
		<div class="mb-4">
			<label class="block text-gray-700 text-sm font-bold mb-2">Введіть 6-значний код з додатку:</label>
			<input type="text" name="two_factor_code" maxlength="6" required class="w-32 text-center border p-2 rounded tracking-widest text-lg">
		</div>

		<button type="submit" class="w-full bg-blue-600 text-white p-2 rounded">Увійти</button>
	</form>
</div>
@endsection