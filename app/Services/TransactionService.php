<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionService
{
    /**
     * @param Account $account
     * @param float $amount
     * @return Transaction
     */
    public function deposit(Account $account, float $amount): Transaction
    {
        return DB::transaction(function () use ($account, $amount) {
            $transaction = $account->receivedTransactions()->create([
                'user_id' => $account->user_id,
                'type' => 'deposit',
                'amount' => $amount,
                'status' => 'completed',
                'transaction_code' => uniqid('txn_'),
            ]);

            $account->increment('bank_balance', $amount);

            return $transaction;
        });
    }

    /**
     * @param Account $sender
     * @param Account $receiver
     * @param float $amount
     * @return Transaction
     */
    public function transfer(Account $sender, Account $receiver, float $amount): Transaction
    {
        if ($sender->bank_balance < $amount) {
            throw ValidationException::withMessages(['error' => 'Saldo insuficiente']);
        }

        return DB::transaction(function () use ($sender, $receiver, $amount) {
            $transaction = Transaction::create([
                'user_id' => $sender->user_id,
                'receiver_id' => $receiver->user_id,
                'type' => 'transfer',
                'amount' => $amount,
                'status' => 'completed',
                'transaction_code' => uniqid('txn_'),
            ]);

            $sender->decrement('bank_balance', $amount);
            $receiver->increment('bank_balance', $amount);

            return $transaction;
        });
    }

    /**
     * @param Transaction $transaction
     * @return Transaction
     */
    public function reverseTransaction(Transaction $transaction): Transaction
    {
        return DB::transaction(function () use ($transaction) {
            if ($transaction->status !== 'completed') {
                throw ValidationException::withMessages(['error' => 'Somente transações concluídas podem ser revertidas']);
            }

            $reversedTransaction = Transaction::create([
                'user_id' => $transaction->receiver_id,
                'receiver_id' => $transaction->user_id,
                'type' => 'reversal',
                'amount' => $transaction->amount,
                'status' => 'completed',
                'transaction_code' => uniqid('txn_'),
                'reversed_transaction_id' => $transaction->id,
            ]);

            Account::where('user_id', $transaction->user_id)->increment('bank_balance', $transaction->amount);
            Account::where('user_id', $transaction->receiver_id)->decrement('bank_balance', $transaction->amount);

            $transaction->update(['status' => 'reversed']);

            return $reversedTransaction;
        });
    }
}

