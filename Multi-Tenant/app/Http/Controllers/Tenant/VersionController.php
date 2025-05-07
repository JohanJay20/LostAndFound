<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class VersionController extends Controller
{
    protected $versionFile;

    public function __construct()
    {
        $this->versionFile = base_path('version.txt');
    }

    public function showVersion()
    {
        $currentVersion = @file_get_contents($this->versionFile) ?: 'v0.0.0';

        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github+json',
            'User-Agent' => 'LaravelApp'
        ])->get('https://api.github.com/repos/JohanJay20/LostAndFound/releases/latest');

        $latestVersion = $response->ok()
            ? $response->json()['tag_name'] ?? 'unknown'
            : 'unknown';

        return view('tenant.version.index', compact('currentVersion', 'latestVersion'));
    }

    public function checkVersion(Request $request)
{
    $response = Http::withHeaders([
        'Accept' => 'application/vnd.github+json',
        'User-Agent' => 'LaravelApp'
    ])->get('https://api.github.com/repos/JohanJay20/LostAndFound/releases/latest');

    if ($response->ok()) {
        $latestVersion = trim($response->json()['tag_name'] ?? '');

        // Validate the version format
        if ($latestVersion && preg_match('/^v\d+\.\d+\.\d+$/', $latestVersion)) {
            // Save the version to the file
            file_put_contents($this->versionFile, $latestVersion);
            
            // Run git commands to update the code to the latest version tag
            $this->updateCodeToVersion($latestVersion);

            return back()->with('success', "Updated to version: $latestVersion");
        }

        return back()->with('error', 'Invalid version format received.');
    }

    return back()->with('error', 'Failed to fetch version from GitHub.');
}
protected function updateCodeToVersion($latestVersion)
{
    $output = null;
    $resultCode = null;

    // Ensure the working directory is clean
    exec('git reset --hard', $output, $resultCode);
    if ($resultCode !== 0) {
        \Log::error('Git reset failed. Output: ' . implode("\n", $output));
        return back()->with('error', 'Failed to reset the working directory.');
    }

    // Stash local changes if you don't want to lose them
    exec('git stash', $output, $resultCode);
    if ($resultCode !== 0) {
        \Log::error('Git stash failed. Output: ' . implode("\n", $output));
        return back()->with('error', 'Failed to stash local changes.');
    }

    // Fetch the latest tags and checkout the required version
    exec('git fetch --tags', $output, $resultCode);
    if ($resultCode === 0) {
        exec('git checkout tags/' . escapeshellarg($latestVersion), $output, $resultCode);
        
        // Log output and result for debugging
        \Log::info('Git Checkout Output: ' . implode("\n", $output));  // Logs output of git checkout
        \Log::info('Git Checkout Result Code: ' . $resultCode);  // Logs result code (0 means success)

        if ($resultCode !== 0) {
            return back()->with('error', 'Failed to checkout to the version: ' . $latestVersion);
        }

        // Optionally, pull latest changes after checkout
        exec('git pull origin ' . escapeshellarg($latestVersion), $output, $resultCode);
        if ($resultCode !== 0) {
            \Log::error('Git Pull Failed. Output: ' . implode("\n", $output));
            return back()->with('error', 'Failed to pull the latest changes.');
        }
    } else {
        \Log::error('Git Fetch Failed. Output: ' . implode("\n", $output)); // Logs the failure output
        return back()->with('error', 'Failed to fetch the latest tags.');
    }

    return back()->with('success', "Updated to version: $latestVersion");
}



}
