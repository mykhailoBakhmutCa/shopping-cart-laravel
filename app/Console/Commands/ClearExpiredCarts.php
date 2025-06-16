<?php

namespace App\Console\Commands;

use App\Models\CartItem;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClearExpiredCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carts:clear-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clearing cart whith expired sessions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sessionLifetimeMinutes = Config::get('session.lifetime', 120);
        $this->info("LifeTime Session: {$sessionLifetimeMinutes} minuts.");
        Log::info("LifeTime Session: {$sessionLifetimeMinutes} minuts.");

        $activeSessionThreshold = Carbon::now()->subMinutes($sessionLifetimeMinutes)->timestamp;

        $this->info("Active sessions line (timestamp): " . $activeSessionThreshold);
        $this->info("Active sessions line: " . Carbon::createFromTimestamp($activeSessionThreshold)->toDateTimeString());
        Log::info("Active sessions line: " . Carbon::createFromTimestamp($activeSessionThreshold)->toDateTimeString());


        $activeSessionIds = [];
        try {
            $activeSessionIds = DB::table('sessions')
                ->where('last_activity', '>=', $activeSessionThreshold)
                ->pluck('id')
                ->toArray();

            $this->info("Found " . count($activeSessionIds) . " active sessions.");
            Log::info("Active session_ids (" . count($activeSessionIds) . "): " . implode(', ', $activeSessionIds));
        } catch (\Exception $e) {
            $this->error("Error getting active sessions: " . $e->getMessage());
            Log::error("Error getting active sessions: " . $e->getMessage());
            return Command::FAILURE;
        }

        $deletedCartItemsCount = CartItem::query()
            ->whereNot('session_id', 'initial_session')
            ->where(function ($query) use ($activeSessionIds) {
                $query->whereNull('session_id')
                    ->orWhere(function ($q) use ($activeSessionIds) {
                        $q->whereNotNull('session_id')
                            ->whereNotIn('session_id', $activeSessionIds);
                    });
            })
            ->delete();


        $this->info("Deleted $deletedCartItemsCount cart items.");
        Log::info("Deleted $deletedCartItemsCount cart items.");

        $this->info("Command carts:clear-expired finish.");
        Log::info("Command carts:clear-expired finish.");


        return Command::SUCCESS;
    }
}
