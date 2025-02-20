<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepositRequest;
use App\Http\Requests\TransferRequest;
use App\Services\AccountService;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    /**
     * @var TransactionService
     */
    private TransactionService $transactionService;

    /**
     * @var AccountService
     */
    private AccountService $accountService;

    /**
     * @param TransactionService $transactionService
     * @param AccountService $accountService
     */
    public function __construct(TransactionService $transactionService, AccountService $accountService)
    {
        $this->transactionService = $transactionService;
        $this->accountService = $accountService;
    }

    /**
     * @param DepositRequest $request
     * @return JsonResponse
     */
    public function deposit(DepositRequest $request): JsonResponse
    {
        $user = auth()->user();
        $transaction = $this->transactionService->deposit($user->account, $request->amount);

        return response()->json([
            'message' => 'Depósito realizado com sucesso!',
            'transaction' => $transaction
        ]);
    }

    /**
     * @param TransferRequest $request
     * @return JsonResponse
     */
    public function transfer(TransferRequest $request): JsonResponse
    {
        $sender = auth()->user();
        $senderAccount = $sender->account;

        $receiverAccount = $this->accountService->findAccountByNumber($request->receiver_account);

        $transaction = $this->transactionService->transfer($senderAccount, $receiverAccount, $request->amount);

        return response()->json([
            'message' => 'Transferência realizada com sucesso!',
            'transaction' => $transaction
        ]);
    }
}
