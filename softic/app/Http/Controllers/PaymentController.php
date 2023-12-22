<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use function Laravel\Prompts\table;

class PaymentController extends Controller
{
    public function paymentStore(Request $request)
    {
        $request->validate([
            'amount' => "required"
        ]);
        $pay = new Payment();
        $pay->user_id = $request->user_id;
        $pay->gateway = $request->gateway;
        $pay->amount = $request->amount;
        $pay->trx = getTrx();
        $pay->save();
        session()->put('trx',$pay->trx);
        return to_route('payment.confirm');
    }

    public function paymentConfirm()
    {
        $trx        = session()->get('trx');
        $payment    = Payment::where('trx',$trx)->with('user')->first();
        $dirname    = $payment->gateway;
        $new        = __NAMESPACE__.'\\Gateway\\'.$dirname.'\\ProcessController';
        $data       = $new::process($payment);
        $data       = json_decode($data);

        if (isset($data->error)) {
            return to_route('home')->with('error',$data->message);
        }
        if (isset($data->redirect)) {
            return redirect($data->redirect_url);
        }
        $pageTitle = 'Payment Confirm';
        $this->transaction($payment,$data);
        return view($data->view, compact('data', 'pageTitle', 'payment'));
    }

    /**
     * Display the specified resource.
     */
    public function transaction($payment,$data)
    {

        $transaction = new Transaction();
        $transaction->user_id = $payment->user_id;
        $transaction->trx = $payment->trx;
        $transaction->amount = $payment->amount;
        $transaction->status = 'success';
        $transaction->save();
    }


}
