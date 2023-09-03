<?php

namespace App\Console\Commands;

use App\Models\Rent;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RentExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rentexpired:cron';

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
        $rents = Rent::whereIn('status', ['unapproved', 'approved'])
            ->get();
        $datetime_now = Carbon::now()->format('Y-m-d H:i:s');
        foreach($rents as $rent){
            $datetime_end = $rent->date_end.' '.$rent->time_end;
            if($datetime_now >= $datetime_end){
                Rent::where('id', $rent->id)
                    ->update([
                        'status' => 'expired'
                    ]);
            }
        }
    }
}
