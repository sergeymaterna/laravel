@extends('layouts.app')

@section('content')
<div class="container max-w-md mx-auto mt-10">
	<h1 class="text-2xl font-bold mb-6">Редагувати користувача: {{ $user->name }}</h1>

	<form method="POST" action="{{ route('users.update', $user->id) }}">
		@csrf
		@method('PUT')

		<div class="mb-4">
			<label class="block text-gray-700">Ім'я</label>
			<input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border p-2 rounded">
		</div>

		<div class="mb-4">
			<label class="block text-gray-700">Email</label>
			<input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border p-2 rounded">
		</div>

		<div class="mb-4">
			<label class="block text-gray-700">Роль</label>
			<select name="role" class="w-full border p-2 rounded">
				<option value="User" @selected($user->role === 'User')>User</option>
				<option value="Admin" @selected($user->role === 'Admin')>Admin</option>
			</select>
		</div>

		<button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Зберегти</button>
		<a href="{{ route('users.index') }}" class="ml-2 text-gray-600">Скасувати</a>
	</form>
</div>
@endsection