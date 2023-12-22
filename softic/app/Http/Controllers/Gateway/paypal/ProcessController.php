<?php

namespace App\Http\Controllers\Gateway\paypal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Http\Controllers\PaymentController;
use App\Lib\CurlRequest;
use App\Models\Order;
use App\Models\User;

class ProcessController extends Controller
{
    public static function process($payment)
    {
        $jsonPath = base_path('public/gateway/paypal.json');
        if (file_exists($jsonPath)) {
            $json = file_get_contents($jsonPath);
            $paypalAcc = json_decode($json, true);
            if (isset($paypalAcc[0]['value'])) {
                $secretKey = $paypalAcc[0]['value'];
            }
        }
        // $paypalAcc = json_decode($payment->paymentGateway->parameter);
   
        $val['cmd'] = '_xclick';
        $val['business'] = trim($secretKey);
        $val['cbt'] = 'Softic';
        $val['currency_code'] = "USD";
        $val['quantity'] = 1;
        $val['item_name'] = "Payment To Softic Account";
        $val['custom'] = "$payment->trx";
        $val['amount'] = round($payment->amount,2);
        $val['return'] = route('home');
        $val['cancel_return'] = route('home');
        $val['notify_url'] = route($payment->gateway);
        $send['val'] = $val;
        $send['view'] = 'payment.paypal';
        $send['method'] = 'post';
        $send['url'] = 'https://www.sandbox.paypal.com/'; // use for sandbod text
    
        $addBalance = User::find($payment->user_id);
        $addBalance->balance += $payment->amount;
        $addBalance->save();

        return json_encode($send);
    }

    public function ipn()
    {
        
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }

        $req = 'cmd=_notify-validate';
        foreach ($myPost as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
            $details[$key] = $value;
        }

        // $paypalURL = "https://ipnpb.sandbox.paypal.com/cgi-bin/webscr?"; // use for sandbox text
        $paypalURL = "https://ipnpb.paypal.com/cgi-bin/webscr?";
        $url = $paypalURL . $req;
        $response = CurlRequest::curlContent($url);

        if ($response == "VERIFIED") {
            $deposit = Payment::where('trx', $_POST['custom'])->orderBy('id', 'DESC')->first();
            // $deposit->detail = $details;
            $deposit->save();
        }
    }
}

