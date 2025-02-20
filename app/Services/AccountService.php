<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Collection;

class AccountService
{

    /**
     * @param $account
     * @return string
     */
    public function getAccountBalance($account): string
    {
        return number_format($account->bank_balance, 2, ',', '.');
    }

    /**
     * @param $user
     * @return Collection
     */
    public function getTransactions($user): Collection
    {
        return Transaction::where('user_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($transaction) => $this->formatTransaction($transaction, $user));
    }

    /**
     * @param Transaction $transaction
     * @param $user
     * @return array
     */
    private function formatTransaction(Transaction $transaction, $user): array
    {
        return [
            'date' => $transaction->created_at->format('d/m/Y H:i'),
            'type' => $this->getTransactionType($transaction, $user),
            'amount' => number_format($transaction->amount, 2, ',', '.'),
            'recipient' => $transaction->user_id === $user->id ? $transaction->receiver->name : $transaction->user->name
        ];
    }

    /**
     * @param Transaction $transaction
     * @param $user
     * @return string
     */
    private function getTransactionType(Transaction $transaction, $user): string
    {
        if ($transaction->user_id === $user->id && $transaction->receiver_id === $user->id) {
            return 'Entrada';
        } elseif ($transaction->user_id != $user->id && $transaction->receiver_id === $user->id) {
            return 'Entrada';
        } elseif ($transaction->user_id === $user->id && $transaction->receiver_id !== $user->id) {
            return 'Saída';
        }
        return 'Desconhecido';
    }

    /**
     * @param string $accountNumber
     * @return Account
     */
    public function findAccountByNumber(string $accountNumber): Account
    {
        return Account::where('account_number', $accountNumber)->firstOrFail();
    }

    /**
     * @param Account $account
     * @return User
     */
    public function getUserByAccount(Account $account): User
    {
        return User::findOrFail($account->user_id);
    }
}
