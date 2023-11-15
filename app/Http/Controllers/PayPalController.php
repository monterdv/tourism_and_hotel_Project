<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{

    private PayPalClient $paypal;

    public function __construct(PayPalClient $paypal)
    {
        $this->paypal = $paypal;
        $this->paypal->setApiCredentials(config('paypal'));
        $this->paypal->setAccessToken($this->paypal->getAccessToken());
    }
    //
    public function payment(Request $request)
    {
        // return $request;
        $slug = $request->slug;

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => url(`http://127.0.0.1:8000/tour/hcmc-tourism-saigon-hundred-year-heritage`),
                // "return_url" => self::cancel(),
                "cancel_url" => url(`http://127.0.0.1:8000/tour/$slug`),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $request->totalPrice,
                        // "value" => "100.00",
                    ]
                ]
            ]
        ]);
        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return response()->json(['redirect_url' => $links['href']]);
                }
            }

            // $orderDetails = $provider->getOrderDetails($response['id']);
            $orderDetails = $provider->capturePaymentOrder($request['token']);

            if (isset($orderDetails['status']) && $orderDetails['status'] == 'COMPLETED') {
                // Call an additional function for further processing
                $this->processPaymentSuccess($orderDetails, $request);

                return response()->json([
                    'success' => true,
                    'data' => ['message' => 'Payment successfully completed.'],
                ]);
            }

            return response()->json([
                'error' => 'Something went wrong.',
            ]);
        } else {
            return response()->json([
                'error' => 'Something went wrong.2',
            ]);
        }
    }

    public function processPaymentSuccess($orderDetails, $request)
    {
        return $request;
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
