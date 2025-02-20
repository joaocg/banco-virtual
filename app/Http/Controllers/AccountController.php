<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class AccountController extends Controller
{
    public function getAccountData()
    {
        $user = auth()->user();
        $account = $user->account;

        $transactions = Transaction::where('user_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();


        return response()->json([
            'balance' => number_format($account->bank_balance, 2, ',', '.'),
            'transactions' => $transactions->map(function ($transaction) use ($user) {

                $options = [
                    'date' => $transaction->created_at->format('d/m/Y H:i'),
                    'type' => $transaction->user_id === $user->id && $transaction->receiver_id === $user->id ? 'Entrada' : 'Saida',
                    'amount' => number_format($transaction->amount, 2, ',', '.'),
                    'recipient' => $transaction->user_id === $user->id ? $transaction->receiver->name : $transaction->user->name
                ];

                if ($transaction->user_id === $user->id && $transaction->receiver_id === $user->id) {
                    $options['type'] = 'Entrada';
                } elseif ($transaction->user_id != $user->id && $transaction->receiver_id === $user->id) {
                    $options['type'] = 'Entrada';
                } elseif ($transaction->user_id === $user->id && $transaction->receiver_id !== $user->id) {
                    $options['type'] = 'Saida';
                }

                return $options;
            })
        ]);
    }
}
