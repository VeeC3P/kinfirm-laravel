<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Jobs\ImportProductJob;

class ImportProducts extends Command
{
    // Potentially I could add params and make this products & stocks JSON?
    protected $signature = 'import:products 
                            {url=https://kinfirm.com/app/uploads/laravel-task/products.json}';
    protected $description = 'Import products from remote JSON file (queued)';

    public function handle(): int
    {
        $url = $this->argument('url');
        $this->info("Fetching products from $url");

        $response = Http::get($url);
        if ($response->failed()) {
            $this->error("Failed to fetch JSON from $url");
            return 1;
        }

        $data = $response->json();
        if (!is_array($data)) {
            $this->error("Invalid JSON structure");
            return 1;
        }

        foreach ($data as $row) {
            ImportProductJob::dispatch($row);
        }

        $this->info("Dispatched " . count($data) . " product jobs to queue.");
        return 0;
    }
}
