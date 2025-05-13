<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\LostAndFound;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use ZipArchive;

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
    public function updateTenant(Request $request)
    {
        $githubApi = env('GITHUB_VERSION_API');
        $response = Http::get($githubApi);

        if (!$response->ok()) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch latest release.']);
        }

        $latestVersion = $response->json('tag_name');
        $downloadUrl = $response->json('zipball_url');

        $tenant = Auth::user()->tenant; // Adjust if your relation is different

        if ($tenant->version === $latestVersion) {
            return response()->json(['success' => false, 'message' => 'Already up to date.']);
        }

        // Download the update
        $zipPath = storage_path("app/update_{$latestVersion}.zip");
        $zipContent = file_get_contents($downloadUrl);
        file_put_contents($zipPath, $zipContent);

        // Extract the update
        $extractPath = base_path(); // Adjust if needed
        $zip = new ZipArchive;
        if ($zip->open($zipPath) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to extract update.']);
        }

        // Run migrations for this tenant
        // tenancy()->initialize($tenant); // If using stancl/tenancy
        Artisan::call('tenants:migrate', ['--tenant' => $tenant->id]);

        // Update the version
        $tenant->version = $latestVersion;
        $tenant->save();

        return response()->json(['success' => true]);
    }
}