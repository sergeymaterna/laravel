<!DOCTYPE html>
<html lang="uk">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Laravel CRUD - @yield('title', 'Posts')</title>
	<!-- Для швидкої стилізації використовуємо CDN Tailwind CSS -->
	<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 antialiased p-6">
<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">

	{{-- Повідомлення про успіх --}}
	@if (session('success'))
	<div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
		{{ session('success') }}
	</div>
	@endif

	@yield('content')
</div>
</body>
</html>