<?php

namespace App\Http\Controllers\Api\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //get all transactions from database with pagination and search by name user
        $pagination = $request->pagination ?? 5;

        $search = $request->search ?? '';

        $transactions = Transaction::whereHas('userId', function($query) use ($search) {
            $query->where('name', 'like', '%'.$search.'%');
        })->with('userId')->with('bookId')->paginate($pagination);

        //response data transactions

        return response()->json([
            'status' => true,
            'message' => 'Data Transaction',
            'data' => $transactions
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate request
        $this->validate($request, [
            'user_id'        => 'required|integer',
            'book_id'        => 'required|integer',
            'borrowed_at'    => 'required|date',
            'returned_at'    => 'required|date',
        ]);

        //create data transaction
        $transaction = Transaction::create([
            'user_id'        => $request->user_id,
            'book_id'        => $request->book_id,
            'borrowed_at'    => $request->borrowed_at,
            'returned_at'    => $request->returned_at,
        ]);

        //response data transaction
        return response()->json([
            'status' => true,
            'message' => 'Transaction Created',
            'data' => $transaction
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //get transaction by id
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'status' => false,
                'message' => 'Transaction Not Found',
                'data' => null
            ], 404);
        }

        //response data transaction
        return response()->json([
            'status' => true,
            'message' => 'Data Transaction',
            'data' => $transaction
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //get transaction by id
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'status' => false,
                'message' => 'Transaction Not Found',
                'data' => null
            ], 404);
        }

        //update data status
        $transaction->update([
            'status' => $request->status
        ]);

        //response data transaction
        return response()->json([
            'status' => true,
            'message' => 'Transaction Updated',
            'data' => $transaction
        ], 200);
    }

    public function getTransactionByUserId(Request $request, string $id)
    {
        //get all transactions by user id from database with pagination
        $pagination = $request->pagination ?? 5;

        $transactions = Transaction::where('user_id', $id)->with('userId')->with('bookId')->paginate($pagination);

        //response data transactions
        return response()->json([
            'status' => true,
            'message' => 'Data Transaction',
            'data' => $transactions
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //get transaction by id
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'status' => false,
                'message' => 'Transaction Not Found',
                'data' => null
            ], 404);
        }

        //delete data transaction
        $transaction->delete();

        //response data transaction
        return response()->json([
            'status' => true,
            'message' => 'Transaction Deleted',
            'data' => null
        ], 200);
    }
}
