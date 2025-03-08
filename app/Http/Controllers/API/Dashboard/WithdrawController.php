<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Traits\APIResponses;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    use APIResponses;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $withdrawPaginator = Transaction::query()
            ->with(['transactionable.user'])
            ->latest()
            ->where('transaction_type', TransactionType::WITHDRAW)
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->paginate($request->input('page_size', 20));


        return $this->okResponse(
            [
                'last_page'     => $withdrawPaginator->currentPage(),
                'last_page'     => $withdrawPaginator->lastPage(),
                'transactions'  => TransactionResource::collection($withdrawPaginator->items())
            ],
            __('Withdraw transactions retrived successfuly')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => "required|string|in:completed,failed"
        ]);


        $transaction = Transaction::where('transaction_type', TransactionType::WITHDRAW)
            ->where('id', $id)
            ->first();

        if (! $transaction) {
            return $this->notFoundResponse(
                [],
                __("No transaction with id: $id")
            );
        }

        $validated = $request->only(['status']);

        if ($request->status == 'completed') {
            $validated['description'] = "تمت الموافقة على طلب السحب";
        }

        $transaction->update($validated);

        return $this->okResponse(
            [],
            __("Transaction updated successfuly")
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
