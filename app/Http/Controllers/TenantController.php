<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\LostAndFound;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use ZipArchive;
use Stancl\Tenancy\Tenant;
use Stancl\Tenancy\Tenancy;

class TenantController extends Controller
{
    // Show the welcome page
    public function welcome()
    {
        // Redirect to dashboard if already logged in
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        $items = LostAndFound::latest()->take(10)->get();
        return view('tenant.welcome', [
            'items' => $items,
            'tenant_organization' => tenant('organization')
        ]);
    }

    // Show the dashboard (only accessible if authenticated)
    public function dashboard()
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('tenant.dashboard');
    }

    // Show the login form
    public function showLoginForm()
    {
        // Redirect to dashboard if already logged in
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('tenant.login');
    }

    // Handle the login process
    public function login(Request $request)
    {
        // Redirect to dashboard if already logged in
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended(route('dashboard'));
        }

        return Redirect::back()->withErrors(['email' => 'Invalid credentials.']);
    }

    // Logout the user
    public function logout()
    {
        Auth::logout();
        return redirect()->route('welcome');
    }
}