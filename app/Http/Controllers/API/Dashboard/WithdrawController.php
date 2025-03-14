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
            ->whereHas('transactionable.user', fn($q) => $q->whereNull('deleted_at'))
            ->with(['transactionable.user'])
            ->latest()
            ->where('transaction_type', TransactionType::WITHDRAW)
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->paginate($request->input('page_size', 20));

        $currentPage = $withdrawPaginator->currentPage();
        $lastPage    = $withdrawPaginator->lastPage();

        $nextPage = $currentPage + 1;
        $prevPage = $currentPage - 1;



        return $this->okResponse(
            [
                'current_page'  => $currentPage,
                'last_page'     => $lastPage,
                'next_page'     => $nextPage > $lastPage ? null : $nextPage,
                'prev_page'     => $prevPage < 1 ? null : $prevPage,
                'next_page_url' => $withdrawPaginator->nextPageUrl(),
                'per_page'  => $withdrawPaginator->perPage(),
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
