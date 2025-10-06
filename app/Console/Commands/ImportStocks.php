<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Jobs\ImportStockJob;

class ImportStocks extends Command
{
    protected $signature = 'import:stocks 
                            {url=https://kinfirm.com/app/uploads/laravel-task/stocks.json}
                            {--test : Run jobs synchronously instead of dispatching to queue}';
    protected $description = 'Import stocks from remote JSON file (queued)';

    public function handle(): int
    {
        $url = $this->argument('url');
        $isTest = $this->option('test');
        $this->info("Fetching stocks from $url");

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

        // foreach ($data as $row) {
        //     ImportStockJob::dispatch($row);
        // }

         foreach ($data as $row) {
            if ($isTest) {
                // Run the job immediately instead of dispatching
                (new \App\Jobs\ImportStockJob($row))->handle();
            } else {
                // Dispatch to queue as usual
                \App\Jobs\ImportStockJob::dispatch($row);
            }
        }

        $this->info("Dispatched " . count($data) . " stock jobs to queue.");
        return 0;
    }
}
