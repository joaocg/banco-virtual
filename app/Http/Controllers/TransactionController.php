<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepositRequest;
use App\Http\Requests\TransferRequest;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    /**
     * @var TransactionService
     */
    protected TransactionService $transactionService;


    /**
     * @param TransactionService $transactionService
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * @param DepositRequest $request
     * @return JsonResponse
     */
    public function deposit(DepositRequest $request): JsonResponse
    {
        $user = auth()->user();

        $account = $user->account;

        $transaction = $this->transactionService->deposit($account, $request->amount);

        return response()->json(['message' => 'Depósito realizado com sucesso!', 'transaction' => $transaction]);
    }

    /**
     * @param TransferRequest $request
     * @return JsonResponse
     */
    public function transfer(TransferRequest $request)
    {
        dd('Chegou');
//        $sender = auth()->user();

        $senderAccount = $sender->account;

        $receiver = User::findOrFail($request->receiver_id);

        $receiverAccount = $receiver->account;

        $transaction = $this->transactionService->transfer($senderAccount, $receiverAccount, $request->amount);

        return response()->json(['message' => 'Transferência realizada com sucesso!', 'transaction' => $transaction]);
    }
}
