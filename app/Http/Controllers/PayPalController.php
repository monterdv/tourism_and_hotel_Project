<?php

namespace App\Http\Controllers;

use GuzzleHttp\Psr7\Message;
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
        try {
            $slug = $request->slug;
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $paypalToken = $provider->getAccessToken();

            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => url("http://127.0.0.1:8000/tour/$slug"),
                    "cancel_url" => url("http://127.0.0.1:8000/tour/$slug"),
                ],
                "purchase_units" => [
                    0 => [
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => $request->totalPrice,
                        ],
                    ],
                ],
            ]);

            // Check if the 'approve' link is present in the response
            $approveLink = collect($response['links'])->firstWhere('rel', 'approve');

            if ($approveLink) {
                return response()->json(['redirect_url' => $approveLink['href']]);
            }
            return response()->json(['error' => 'Something went wrong.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
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
