<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\BookingTour;
use App\Models\slot_tour;
use App\Models\cart_tour;
use App\Models\tour_Time;


class paymentController extends Controller
{
    //
    public function payment(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $requestData = json_encode($request->all());

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('success.tour', ['requestData' => $requestData]),
                "cancel_url" => route('cancel.tour')
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" =>  $request->totalPrice,
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return response()->json(['redirect_url' => $link['href']]);
                }
            }
        } else {
            return redirect()->route('cancel.tour');
        }
    }

    public function paymentSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request->token);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {

            $requestData = json_decode($request->query('requestData'), true);
            // return  $requestData;
            $code = $this->generateUniqueTourCode();

            $BookingTour = new BookingTour;
            $BookingTour->bookings_Code = $code;
            $BookingTour->tour_id = $requestData['tour_id'];
            $BookingTour->tourTime_id = $requestData['tourTime_id'];
            $BookingTour->adults = $requestData['adults'];
            $BookingTour->children = $requestData['children'];
            $BookingTour->total_price = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
            $BookingTour->user_id = $requestData['user_id'];
            $BookingTour->payment_id = $requestData['payment_id'];
            $BookingTour->status = "money paid";
            $BookingTour->save();

            $record = cart_tour::find($requestData['cart_id']);
            $record->delete();
            $tourTime = tour_Time::find($requestData['tourTime_id']);
            $tourTime->slots_booked += $requestData['adults'] + $requestData['children'];
            $tourTime->save();

            $customerData = $requestData['customer'];

            for ($i = 0; $i < count($customerData); $i++) {
                $slot_tour = new slot_tour;
                $slot_tour->name = $customerData[$i]['name'];
                $slot_tour->email = $customerData[$i]['email'];
                $slot_tour->phone = $customerData[$i]['phone'];
                $slot_tour->passport = $customerData[$i]['passport'];
                $slot_tour->nationality_id = $customerData[$i]['nationality'];
                $slot_tour->bookings_tour_id = $BookingTour->id;
                if ($i < $requestData['adults']) {
                    $slot_tour->type = 'adult';
                }
                $slot_tour->save();
            };
            return redirect()->to("http://127.0.0.1:8000/checkout/{$BookingTour->bookings_Code}/tour");
        } else {
            return redirect()->route('cancel.tour');
        }
    }
    public function generateUniqueTourCode()
    {
        do {
            $letters = "BK";
            $digits = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $code = $letters . $digits;
        } while (BookingTour::where('bookings_Code', $code)->exists());

        return $code;
    }
}
