<?php

namespace App\Http\Controllers\Gateway\stripe;

use App\Http\Controllers\Controller;
use App\Models\Gateway;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    public static function process($payment)
    {
        $jsonPath = base_path('public/gateway/stripe.json');
        if (file_exists($jsonPath)) {
            $json = file_get_contents($jsonPath);
            $StripeAcc = json_decode($json, true);
            if (isset($StripeAcc[0]['secret_key'])) {
                $secretKey = $StripeAcc[0]['secret_key'];
            }
        }
        $alias = $payment->gateway;
        \Stripe\Stripe::setApiKey("$secretKey");
        try {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => "USD",
                            'unit_amount' => round($payment->amount, 2) * 100,
                            'product_data' => [
                                'name' => 'Softic',
                                'description' => 'Payment  with Stripe',
                            ],
                        ],
                        'quantity' => 1,
                    ]
                ],
                'mode' => 'payment',
                'cancel_url' => route('home'),
                'success_url' => route('home'),
            ]);
        } catch (\Exception $e) {
            $send['error'] = true;
            $send['message'] = $e->getMessage();
            return json_encode($send);
        }
        $send['view'] = 'payment.' . $alias;
        $send['session'] = $session;
        $send['StripeJSAcc'] = $StripeAcc;
        $addBalance = User::find($payment->user_id);
        $addBalance->balance += $payment->amount;
        $addBalance->save(); 
        return json_encode($send);
    }

//    public function ipn(Request $request)
//    {
//        $StripeAcc = Gateway::where('code','stripe')->first();
//        $gateway_parameter = json_decode($StripeAcc->gateway_parameter);
//        \Stripe\Stripe::setApiKey($gateway_parameter->secret_key);
//
//        // You can find your endpoint's secret in your webhook settings
//        $endpoint_secret = $gateway_parameter->end_point; // main
//        $payload = @file_get_contents('php://input');
//        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
//
//        $event = null;
//        try {
//            $event = \Stripe\Webhook::constructEvent(
//                $payload,
//                $sig_header,
//                $endpoint_secret
//            );
//        } catch (\UnexpectedValueException $e) {
//            // Invalid payload
//            http_response_code(400);
//            exit();
//        } catch (\Stripe\Exception\SignatureVerificationException $e) {
//            // Invalid signature
//            http_response_code(400);
//            exit();
//        }
//
//        // Handle the checkout.session.completed event
//        if ($event->type == 'checkout.session.completed') {
//            $session = $event->data->object;
//        }
//        http_response_code(200);
//    }
}
