<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use ZipArchive;

class AppUpdateController extends Controller
{
    public function performUpdate(Request $request)
    {
        $version = $request->input('version');
        if (!$version) {
            return response()->json(['error' => 'No version specified.'], 400);
        }

        // Fetch the release from GitHub
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GITHUB_TOKEN'),
            'Accept' => 'application/vnd.github.v3+json'
        ])->get("https://api.github.com/repos/JohanJay20/LostAndFound/releases/tags/$version");

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to fetch release.'], 500);
        }

        $release = $response->json();
        $assets = $release['assets'] ?? [];

        if (empty($assets)) {
            return response()->json(['error' => 'No downloadable assets in this release.'], 404);
        }

        // Download the update ZIP
        $downloadUrl = $assets[0]['browser_download_url'];
        $zipDir = storage_path('app');
        $zipFilePath = $zipDir . "/update-{$version}.zip";

        // Ensure the directory exists
        if (!File::exists($zipDir)) {
            File::makeDirectory($zipDir, 0755, true);
        }

        $download = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GITHUB_TOKEN')
        ])->get($downloadUrl);

        if ($download->failed()) {
            return response()->json(['error' => 'Failed to download update file.'], 500);
        }

        file_put_contents($zipFilePath, $download->body());

        // === AUTO-APPLY UPDATE ===
        try {
            $extractPath = storage_path("app/update-temp-{$version}");

            // Ensure extract path exists
            if (!File::exists($extractPath)) {
                File::makeDirectory($extractPath, 0755, true);
            }

            $zip = new ZipArchive;
            if ($zip->open($zipFilePath) === TRUE) {
                $zip->extractTo($extractPath);
                $zip->close();
            } else {
                return response()->json(['error' => 'Failed to unzip the update file.'], 500);
            }

            // Find the top-level directory in the extracted zip
            $dirs = array_filter(glob($extractPath . '/*'), 'is_dir');
            if (count($dirs) === 1) {
                $sourceDir = $dirs[0];
            } else {
                $sourceDir = $extractPath;
            }

            // Recursively copy files from $sourceDir to base_path()
            $this->recurseCopy($sourceDir, base_path());

            // Clean up
            $this->deleteDirectory($extractPath);
            unlink($zipFilePath);

            // Clear Laravel caches
            Artisan::call('config:clear');
            Artisan::call('view:clear');

            // Optionally update version.txt
            file_put_contents(base_path('version.txt'), $version);

            return response()->json(['message' => "Update $version applied successfully."]);
        } catch (\Exception $e) {
            \Log::error('Update failed: ' . $e->getMessage());
            return response()->json(['error' => 'Update failed: ' . $e->getMessage()], 500);
        }
    }

    public function checkForUpdate()
    {
        $versionFilePath = base_path('version.txt');
        if (!file_exists($versionFilePath)) {
            return response()->json(['error' => 'Version file not found.'], 500);
        }

        $currentVersion = trim(file_get_contents($versionFilePath));

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GITHUB_TOKEN'),
            'Accept' => 'application/vnd.github.v3+json'
        ])->get('https://api.github.com/repos/JohanJay20/LostAndFound/releases');

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to fetch release info.'], 500);
        }

        $releases = collect($response->json())
            ->filter(fn($release) => isset($release['tag_name']) && $release['tag_name'] !== $currentVersion)
            ->map(fn($release) => [
                'version' => $release['tag_name'],
                'name' => $release['name'] ?? $release['tag_name'],
                'published_at' => $release['published_at'],
                'body' => $release['body'] ?? '',
            ])
            ->values();

        return response()->json([
            'current_version' => $currentVersion,
            'available_updates' => $releases,
            'has_update' => $releases->isNotEmpty(),
        ]);
    }

    private function recurseCopy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst, 0755, true);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recurseCopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    private function deleteDirectory($dir)
    {
        if (!file_exists($dir)) return;
        if (!is_dir($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            $this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item);
        }
        rmdir($dir);
    }
}