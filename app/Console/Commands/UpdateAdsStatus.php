<?php

namespace App\Console\Commands;

use App\Models\Ad;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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

        //  The maxmum number of active ads in sametime.
        $activeAdsLimit  = 10;

        //  The maxmum number of  ads in waiting list.
        $waitingAdsLimit = 10;

        $actualActivAds = Ad::whereStatus('approved')
            ->where('end_date', '>', now()) // Not ended
            ->count();

        $waitingAdsLimit = $actualActivAds < $activeAdsLimit
            ? $activeAdsLimit - $actualActivAds
            : $waitingAdsLimit;

        // activate new ads from waiting list
        Ad::whereStatus('waiting')
            ->where('end_date', '>', now())
            ->latest()
            ->limit($waitingAdsLimit)
            ->update([
                'status' => 'approved'
            ]);

        Log::info("Update ads status proccessed");
    }
}
