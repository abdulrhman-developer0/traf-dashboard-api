<?php

namespace App\Console\Commands;

use App\Models\Ad;
use Illuminate\Console\Command;

class UpdateAdsStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-ads-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {


        $activeAdsLimit  = 10;

        $waitingAdsLimit = 10;

        $actualActivAds = Ad::where('end_date', '>', now())
            ->whereStatus('approved')
            ->count();

        $waitingAdsLimit = $actualActivAds < $activeAdsLimit
            ? $activeAdsLimit - $activeAdsLimit
            : $waitingAdsLimit;

            // activate new ads from waiting list
        Ad::whereStatus('waiting')
            ->where('end_date', '>', now())
            ->latest()
            ->limit($waitingAdsLimit)
            ->update([
                'status' => 'approved'
            ]);
    }
}
