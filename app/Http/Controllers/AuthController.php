<?php

namespace App\Http\Controllers;

use App\Models\Tenant\AdminAndDriver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('admin_and_driver_id')) {
            return redirect()->route('parents.index');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = AdminAndDriver::query()
            ->where('username', trim($credentials['username']))
            ->where('is_active', true)
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withErrors(['username' => 'Invalid username or password.'])
                ->onlyInput('username');
        }

        $request->session()->regenerate();
        $request->session()->put('admin_and_driver_id', $user->id);

        return redirect()->intended(route('parents.index'));
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('admin_and_driver_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
