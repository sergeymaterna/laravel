@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10">
	<h1 class="text-2xl font-bold mb-6">Список користувачів</h1>

	<table class="w-full bg-white border border-gray-200 shadow-sm rounded">
		<thead>
		<tr class="bg-gray-100 border-b">
			<th class="p-3 text-left">ID</th>
			<th class="p-3 text-left">Ім'я</th>
			<th class="p-3 text-left">Email</th>
			<th class="p-3 text-left">Роль</th>
			<th class="p-3 text-right">Дії</th>
		</tr>
		</thead>
		<tbody>
		@foreach($users as $user)
		<tr class="border-b hover:bg-gray-50">
			<td class="p-3">{{ $user->id }}</td>
			<td class="p-3">{{ $user->name }}</td>
			<td class="p-3">{{ $user->email }}</td>
			<td class="p-3">{{ $user->role }}</td>
			<td class="p-3 text-right">
				<a href="{{ route('users.edit', $user->id) }}" class="text-blue-600 hover:underline">Редагувати</a>
			</td>
		</tr>
		@endforeach
		</tbody>
	</table>

	<div class="mt-4">
		{{ $users->links() }}
	</div>
</div>
@endsection