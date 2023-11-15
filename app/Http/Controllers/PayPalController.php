<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{
    //
    public function payment(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.payment.success'),
                "cancel_url" => route('paypal.payment/cancel'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        // "value" => $request->priceTotal,
                        "value" => "100.00",
                    ]
                ]
            ]
        ]);
        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    // return redirect()->away($links['href']);
                    return response()->json(['redirect_url' => $links['href']]);
                }
            }

            return response()->json([
                'error' => 'Something went wrong.',
            ]);
        } else {
            return response()->json([
                'error' => 'Something went wrong.',
            ]);
        }
    }

    public function paymentCancel()
    {
        return response()->json([
            'error' => 'You have canceled the transaction..',
        ]);
    }

    public function paymentSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            return response()->json([
                'success' => true,
                'data' => ['message' => 'Payment successfully completed.'],
            ]);
        } else {
            return response()->json([
                'error' => 'Something went wrong.',
            ]);
        }
    }
}
