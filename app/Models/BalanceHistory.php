<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BalanceHistory extends Model
{
    protected $table = 'balance_history';

    protected $fillable = [
        'account_id',
        'currency_id',
        'amount',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;
}
