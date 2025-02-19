<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Account;
use Illuminate\Support\Str;

class UserObserver
{
    public function created(User $user)
    {
        Account::create([
            'user_id' => $user->id,
            'account_number' => Str::padLeft(rand(0, 999999), 6, '0'),
            'bank_balance' => 0.0
        ]);
    }
}
