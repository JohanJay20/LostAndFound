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
        try {
            \Log::info('Update request received', [
                'version' => $request->input('version'),
                'url' => $request->url(),
                'method' => $request->method()
            ]);

            $version = $request->input('version');
            if (!$version) {
                return response()->json(['error' => 'No version specified.'], 400);
            }

            // Direct ZIP download URL for the tag
            $downloadUrl = "https://github.com/JohanJay20/LostAndFound/archive/refs/tags/{$version}.zip";
            $zipDir = storage_path('app');
            $zipFilePath = $zipDir . "/update-{$version}.zip";

            // Ensure the directory exists
            if (!File::exists($zipDir)) {
                File::makeDirectory($zipDir, 0755, true);
            }

            // Download the ZIP file
            $download = Http::get($downloadUrl);

            if ($download->failed()) {
                \Log::error('Download failed', [
                    'status' => $download->status(),
                    'url' => $downloadUrl
                ]);
                return response()->json(['error' => 'Failed to download update file.'], 500);
            }

            file_put_contents($zipFilePath, $download->body());

            // === AUTO-APPLY UPDATE ===
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

            // Directories to exclude from update
            $excludeDirs = [
                '.git',
                'vendor',
                'node_modules',
                'storage',
                'bootstrap/cache'
            ];

            // Files to exclude from update
            $excludeFiles = [
                '.env',
                '.env.example',
                'composer.lock',
                'package-lock.json'
            ];

            // Recursively copy files from $sourceDir to base_path()
            $this->recurseCopy($sourceDir, base_path(), $excludeDirs, $excludeFiles);

            // Clean up
            $this->deleteDirectory($extractPath);
            unlink($zipFilePath);

            // Clear Laravel caches
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            // Update version.txt
            file_put_contents(base_path('version.txt'), $version);

            return response()->json(['message' => "Update $version applied successfully."]);
        } catch (\Exception $e) {
            \Log::error('Update failed with exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
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

    private function recurseCopy($src, $dst, $excludeDirs = [], $excludeFiles = [])
    {
        $dir = opendir($src);
        @mkdir($dst, 0755, true);
        
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $srcPath = $src . '/' . $file;
                $dstPath = $dst . '/' . $file;

                // Skip excluded directories
                if (is_dir($srcPath) && in_array($file, $excludeDirs)) {
                    continue;
                }

                // Skip excluded files
                if (is_file($srcPath) && in_array($file, $excludeFiles)) {
                    continue;
                }

                if (is_dir($srcPath)) {
                    $this->recurseCopy($srcPath, $dstPath, $excludeDirs, $excludeFiles);
                } else {
                    try {
                        copy($srcPath, $dstPath);
                    } catch (\Exception $e) {
                        \Log::warning("Failed to copy file: {$srcPath} to {$dstPath}", [
                            'error' => $e->getMessage()
                        ]);
                        // Continue with other files even if one fails
                        continue;
                    }
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