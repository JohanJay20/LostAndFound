<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchLatestVersion extends Command
{
    protected $signature = 'version:fetch';
    protected $description = 'Fetch the latest release version from GitHub';

    public function handle()
    {
        $apiUrl = env('GITHUB_VERSION_API');
    
        $response = Http::get($apiUrl);
    
        if ($response->successful()) {
            $version = $response->json()['tag_name'] ?? 'v0.0.0';
            file_put_contents(base_path('version.txt'), $version);
            $this->info("Latest version fetched: $version");
        } else {
            $this->error('Failed to fetch version from GitHub.');
        }
    }
    
}
