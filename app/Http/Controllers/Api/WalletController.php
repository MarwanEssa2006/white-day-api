<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletPayment;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function show(Request $request)
    {
        $wallet = Wallet::where('user_id', $request->user()->user_id)->firstOrFail();

        $transactions = WalletPayment::where('user_id', $request->user()->user_id)
            ->orderBy('payment_date', 'desc')
            ->get()
            ->map(fn($t) => [
                'id'             => $t->transaction_id,
                'amount'         => $t->amount,
                'commission'     => $t->commission,
                'balance_after'  => $t->balance_after,
                'method'         => $t->payment_method,
                'date'           => $t->payment_date,
                'booking_id'     => $t->booking_id,
            ]);

        return response()->json([
            'balance'      => $wallet->balance,
            'transactions' => $transactions,
        ]);
    }

    public function topup(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $wallet = Wallet::where('user_id', $request->user()->user_id)->firstOrFail();
        $wallet->balance += $data['amount'];
        $wallet->save();

        WalletPayment::create([
            'payment_method' => 'topup',
            'amount'         => $data['amount'],
            'commission'     => 0,
            'balance_after'  => $wallet->balance,
            'payment_date'   => now(),
            'user_id'        => $request->user()->user_id,
            'booking_id'     => null,
        ]);

        return response()->json([
            'message'     => 'Wallet topped up successfully',
            'new_balance' => $wallet->balance,
        ]);
    }
}