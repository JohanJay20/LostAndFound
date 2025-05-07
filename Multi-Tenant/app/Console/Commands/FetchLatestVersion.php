<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchLatestVersion extends Command
{
    protected $signature = 'version:fetch';
    protected $description = 'Fetch latest GitHub release version';

    public function handle()
    {
        $response = Http::get('https://api.github.com/repos/JohanJay20/LostAndFound/releases/latest');

        if ($response->ok()) {
            $latestVersion = $response->json()['tag_name'] ?? 'unknown';
            file_put_contents(base_path('version.txt'), $latestVersion);
            $this->info("Latest version: $latestVersion");
        } else {
            $this->error("Failed to fetch version from GitHub.");
        }
    }
}
