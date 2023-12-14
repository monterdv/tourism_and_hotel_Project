<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\tour_Time;
use App\Models\BookingTour;
use App\Models\slot_tour;

class DeleteOverdueTours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:overdue-tours';

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
        // echo "Tlee dep trai";
        $overdueTours = BookingTour::where('created_at', '<', now()->subDays(7))
            ->where('status_payment', '=', 'unpaid')
            ->get();

        if ($overdueTours) {
            foreach ($overdueTours as $tour) {
                slot_tour::where('bookings_tour_id', $tour->id)->delete();
                $tour->delete();
            }
        }
    }
}
