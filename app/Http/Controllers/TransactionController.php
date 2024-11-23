<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Order;
use App\Models\User;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use Illuminate\Http\Request;
use App\Models\Customer;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.transactions.index', [
            'active' => 'data',
            'transactions' => Transaction::latest()
                ->filter(request(['search']))
                ->paginate(7)
                ->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.transactions.create', [
            'users' => User::all(),
            'customers' => Customer::all(),
            'active' => Data::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTransactionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransactionRequest $request)
    {
        // Create a new transaction
        Transaction::create($request->validated());

        return redirect('/dashboard/transactions')->with('success', 'Transaksi telah ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return view('dashboard.transactions.show', [
            'transaction' => $transaction,
            'active' => 'data',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        return view('dashboard.transactions.edit', [
            'users' => User::all(),
            'transaction' => $transaction,
            'customers' => Customer::all(),
            'active' => 'data',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTransactionRequest  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $transaction->update($request->validated());

        return redirect('/dashboard/transactions')->with('success', 'Transaksi telah diubah.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction, Request $request)
    {
        // Ensure the transaction exists before attempting to delete
        if ($transaction) {
            $transaction->delete();
            Order::where('no_nota', $request->no_nota)->delete();

            return redirect('/dashboard/transactions')->with('success', 'Transaksi telah dihapus.');
        }

        return redirect('/dashboard/transactions')->with('error', 'Transaksi tidak ditemukan.');
    }
}
