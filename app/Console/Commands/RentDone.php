<?php

namespace App\Console\Commands;

use App\Models\Rent;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RentDone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rentdone:cron';

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
        $rents = Rent::where('status', 'approved')
            ->get();
        $datetime_now = Carbon::now()->format('Y-m-d H:i:s');
        foreach($rents as $rent){
            $datetime_end = $rent->date_end.' '.$rent->time_end;
            if($datetime_now >= $datetime_end){
                Rent::where('id', $rent->id)
                    ->update([
                        'status' => 'done'
                    ]);
                $store_report = new Report();
                $store_report->rent_id = $rent->id;
                $store_report->date_report = Carbon::now()->format('Y-m-d');
                $store_report->save();
            }
        }
    }
}
