<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WalletPayment extends Model
{
    protected $table      = 'wallet_payment';
    protected $primaryKey = 'transaction_id';
    public    $timestamps = false;

    protected $fillable = [
        'payment_method',
        'amount',
        'commission',
        'balance_after',
        'payment_date',
        'booking_id',
        'user_id',
    ];
}