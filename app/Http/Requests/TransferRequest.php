<?php

namespace App\Http\Requests;

use App\Models\Account;
use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receiver_account' => [
                'required',
                function ($attribute, $value, $fail) {
                    $account = Account::where('account_number', $value)->first();

                    if (!$account) {
                        return $fail('A conta informada não existe.');
                    }

                    if ($account->id === auth()->user()->account->id) {
                        return $fail('Você não pode transferir para sua própria conta.');
                    }
                },
            ],
            'amount' => 'required|numeric|min:0.01',
        ];
    }

}


