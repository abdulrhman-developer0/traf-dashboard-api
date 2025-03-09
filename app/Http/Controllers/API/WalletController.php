<?php

namespace App\Http\Controllers\API;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\WalletResource;
use App\Models\Transaction;
use App\Traits\APIResponses;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    use APIResponses;

    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $wallet = $user->wallet()->firstOrCreate();

        return $this->okResponse(
            WalletResource::make($wallet),
            __('wallet_retrieved_successfuly')
        );
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount'        => ['required', 'numeric', 'min:0.00'],
            'reference_id'  => ['required', 'string', 'max:255']
        ]);

        $user = $request->user();

        $wallet = $user->wallet()->firstOrCreate();

        $transaction = $wallet->deposit(
            amount: $request->amount,
            description: " تم شحن المحفظة بنجاح",
            refId: $request->reference_id
        );

        return $this->okResponse(
            [
                'balance'       => $wallet->balance,
                'transaction'  => TransactionResource::make($transaction)
            ],
            __('transaction_created_successful')
        );
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount'        => ['required', 'numeric', 'min:0.00'],
        ]);


        $user = $request->user();

        $wallet = $user->wallet()->firstOrCreate();

        if ($request->amount > $wallet->balance) {
            return $this->badResponse(
                [
                    'balance'   => $wallet->balance,
                    'amount'    => $request->amount,
                ],
                __('هnsufficient_balance')
            );
        }


        $transaction = $wallet->withdraw(
            amount: $request->amount,
            description: "طلب السحب قيد الانتظار",
            refId: null
        );

        return $this->okResponse(
            [
                'balance'       => $wallet->balance,
                'transaction'  => TransactionResource::make($transaction)
            ],
            __('transaction_created_successful')
        );
    }

    public function updateBankDetails(Request $request)
    {
        $request->validate([
            'account_holder_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:30',
            'iban' => 'required|string|max:34',
            'bank_name' => 'required|string|max:255',
        ]);

        $wallet = $request->user()->initializeWallet();

        $wallet->update($request->only(['account_holder_name', 'account_number', 'iban', 'bank_name']));

        return $this->okResponse(
            [],
            'Bank details updated successfully'
        );
    }
}
