<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['id','user_id','gateway','amount','trx'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

//    public function paymentGateway()
//    {
//        return $this->belongsTo(Gateway::class, 'gateway','code');
//    }
}
