<?php

namespace App\Console\Commands;

use App\Models\CartItem;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ClearExpiredCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carts:clear-expired-carts';

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

        $expirationTimestamp = Carbon::now()->subMinutes($sessionLifetimeMinutes)->timestamp;
        $this->info("Expired timestamm line: " . Carbon::createFromTimestamp($expirationTimestamp)->toDateTimeString());

        $expiredSessionIds = [];
        try {
            $expiredSessionIds = DB::table('sessions')
                ->where('last_activity', '<', $expirationTimestamp)
                ->pluck('id')
                ->toArray();
        } catch (\Exception $e) {
            $this->error("Error getting expired sessions: " . $e->getMessage());
            return Command::FAILURE;
        }

        if (empty($expiredSessionIds)) {
            $this->info("Expired sessions not found.");
            return Command::SUCCESS;
        }

        $this->info("Found " . count($expiredSessionIds) . " expired sessions.");

        $deletedCartItemsCount = CartItem::whereIn('session_id', $expiredSessionIds)->delete();

        $deletedSessionsCount = DB::table('sessions')->whereIn('id', $expiredSessionIds)->delete();

        $this->info("Deleted $deletedCartItemsCount cart rows.");
        $this->info("Deleted $deletedSessionsCount expired sessions.");

        return Command::SUCCESS;
    }
}
