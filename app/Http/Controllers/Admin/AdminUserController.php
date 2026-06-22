<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /** List admin users and the add-user form. */
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => Admin::query()->orderBy('name')->get(),
        ]);
    }

    /** Create a new admin user. */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:190', 'unique:admins,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], // hashed automatically via the model cast
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', "Admin user “{$data['name']}” added.");
    }

    /** Remove an admin user (never yourself or the last remaining admin). */
    public function destroy(Admin $user): RedirectResponse
    {
        if (auth('admin')->id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot remove your own account.');
        }

        if (Admin::query()->count() <= 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot remove the last admin account.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('status', "Admin user “{$name}” removed.");
    }
}
