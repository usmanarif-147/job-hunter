<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;                                                                                                                                                 
use Illuminate\Support\Facades\DB;
use App\Models\JobListing;

class FlushJobViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:flush-views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush pending Redis view counters into the job_listings table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $prefix = config('database.redis.options.prefix'); // e.g. "laravel-database-"
        $cursor = 0;

        do {
            // phpredis returns [cursor, keys] when there are matches, or false when the
            // iteration is complete (including the case where no keys matched at all).
            $result = Redis::scan($cursor, ['match' => 'job:views:*', 'count' => 100]);

            if ($result === false) {
                break;
            }

            [$cursor, $keys] = $result;

            foreach ($keys as $prefixedKey) {
                // Strip the prefix so the facade doesn't double-prefix on get/del
                $key = str_starts_with($prefixedKey, $prefix)
                    ? substr($prefixedKey, strlen($prefix))
                    : $prefixedKey;

                $id = (int) str_replace('job:views:', '', $key);

                DB::transaction(function () use ($key, $id) {
                    $delta = (int) Redis::get($key);
                    if ($delta === 0) {
                        return;
                    }
                    JobListing::where('id', $id)->increment('views', $delta);
                    Redis::del($key);
                });
            }
        } while ($cursor != 0); // loose comparison — phpredis returns "0" string when done

        return self::SUCCESS;

    }
}
