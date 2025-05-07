<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

        // (Optional) Also fetch the latest from GitHub to display both
        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github+json',
            'User-Agent' => 'LaravelApp'
        ])->get('https://api.github.com/repos/JohanJay20/LostAndFound/releases/latest');

        $latestVersion = $response->ok()
            ? $response->json()['tag_name'] ?? 'unknown'
            : 'unknown';

        return view('version.index', compact('currentVersion', 'latestVersion'));
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
    // Run the git commands to fetch and checkout the latest version tag
    $output = null;
    $resultCode = null;

    // Fetch the latest tags and checkout the required version
    exec('git fetch --tags', $output, $resultCode);
    if ($resultCode === 0) {
        exec('git checkout tags/' . escapeshellarg($latestVersion), $output, $resultCode);
        if ($resultCode !== 0) {
            return back()->with('error', 'Failed to checkout to the version: ' . $latestVersion);
        }
    } else {
        return back()->with('error', 'Failed to fetch the latest tags.');
    }
}

}
