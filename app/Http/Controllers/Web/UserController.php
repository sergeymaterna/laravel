<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
	public function index()
	{
		$users = User::paginate(10);

		return view('users.index', compact('users'));
	}

	public function edit(User $user)
	{
		return view('users.edit', compact('user'));
	}

	public function update(Request $request, User $user)
	{
		$validated = $request->validate([
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'email', 'unique:users,email,' . $user->id],
			'role' => ['required', 'string', 'in:User,Admin'],
		]);

		$user->update($validated);

		return redirect()->route('users.index')->with('success', 'Дані користувача оновлено.');
	}
}