<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list()
    {
        $transactions = Transaction::with('user')->get();
        return view('transaction',compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function details($id)
    {
        $transactions = Transaction::where('user_id',$id)->with('user')->latest()->get();
        return view('trx_details',compact('transactions'));
    }


}
