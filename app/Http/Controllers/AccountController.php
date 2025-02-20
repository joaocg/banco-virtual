<?php

namespace App\Http\Controllers;

use App\Services\AccountService;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    /**
     * @var AccountService
     */
    private AccountService $accountService;

    /**
     * @param AccountService $accountService
     */
    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * @return JsonResponse
     */
    public function getAccountData(): JsonResponse
    {
        $user = auth()->user();
        $account = $user->account;

        return response()->json([
            'balance' => $this->accountService->getAccountBalance($account),
            'transactions' => $this->accountService->getTransactions($user)
        ]);
    }
}
