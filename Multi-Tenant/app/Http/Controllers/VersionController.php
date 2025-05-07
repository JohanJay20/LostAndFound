<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VersionController extends Controller
{
    public function showVersion()
    {
        $currentVersion = @file_get_contents(base_path('version.txt')) ?: 'v0.0.0';
        return view('version.index', compact('currentVersion'));
    }

    public function checkVersion(Request $request)
    {
        $response = Http::get('https://api.github.com/repos/JohanJay20/LostAndFound/releases/latest');

        if ($response->ok()) {
            $latestVersion = $response->json()['tag_name'] ?? 'unknown';
            file_put_contents(base_path('version.txt'), trim($latestVersion));
            
            return back()->with('success', "Updated to version: $latestVersion");
        }
        
        return back()->with('error', 'Failed to check version');
    }
}