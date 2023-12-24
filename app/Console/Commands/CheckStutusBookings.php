<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\tour_Time;
use App\Models\Tour;
use App\Models\BookingTour;
use Carbon\Carbon;

class CheckStutusBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:stutus-bookings';

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
        $changeStatus = BookingTour::where('status_booking', '!=', 'completed')->get();

        if ($changeStatus) {
            foreach ($changeStatus as $tour) {
                $time = tour_Time::where('id', $tour->tourTime_id)->first();
                $durationTour = tour::where('id', $tour->tour_id)->first();

                $date = Carbon::parse($time->date);
                $duration = $durationTour->duration;

                // Tính ngày kết thúc bằng cách thêm thời lượng vào ngày bắt đầu
                $endDate = $date->addDay($duration);
                $now = Carbon::now();

                if ($now->greaterThan($endDate)) {
                    $tour->update(['status_booking' => 'completed']);
                } else {
                    if ($now->between($date, $endDate)) {
                        $tour->update(['status_booking' => 'in_progress']);
                    }
                }
            }
        }
    }
}
