<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BookingHotel;
use Carbon\Carbon;

class CheckStatusbookingHotel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check-statusbooking-hotel';

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
        //
        $changeStatus = BookingHotel::where('status_booking', '!=', 'completed')->get();

        if ($changeStatus) {
            foreach ($changeStatus as $item) {
                $start_date = $item->start_date;
                $end_date = $item->end_date;
                $now = Carbon::now();

                if ($now->between($start_date, $end_date)) {
                    $item->update(['status_booking' => 'in_progress']);
                }
                if ($now->greaterThan($end_date)) {
                    $item->update(['status_booking' => 'completed']);
                }
            }
        }
    }
}
