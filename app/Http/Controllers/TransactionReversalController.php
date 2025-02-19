<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionReversalController extends Controller
{
    /**
     * @var TransactionService
     */
    protected $transactionService;

    /**
     * @param TransactionService $transactionService
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * @param Request $request
     * @param $transactionId
     * @return JsonResponse
     */
    public function reverse(Request $request, $transactionId): JsonResponse
    {
        $transaction = Transaction::findOrFail($transactionId);

        if ($transaction->user_id !== auth()->id()) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        $reversedTransaction = $this->transactionService->reverseTransaction($transaction);

        return response()->json(['message' => 'Transação revertida com sucesso', 'transaction' => $reversedTransaction]);
    }
}
