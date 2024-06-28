<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Transaction;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        //get total user from database
        $total_user = User::count();
        //get total book from database
        $total_book = Book::count();
        // get total transaction where status is 'borrowed'
        $transaction_borrowed = Transaction::where('status', 'borrowed')->count();
        // get total transaction where status is 'returned'
        $transaction_returned = Transaction::where('status', 'returned')->count();

        return response()->json([
            'status' => true,
            'message' => 'Dashboard Data',
            'data' => [
                'total_user' => $total_user,
                'total_book' => $total_book,
                'transaction_borrowed' => $transaction_borrowed,
                'transaction_returned' => $transaction_returned
            ]
        ], 200);
    }

    public function indexByUserId($id)
    {
        // get total transaction where status is 'borrowed' by user id
        $transaction_borrowed = Transaction::where('status', 'borrowed')->where('user_id', $id)->count();
        // get total transaction where status is 'returned' by user id
        $transaction_returned = Transaction::where('status', 'returned')->where('user_id', $id)->count();

        return response()->json([
            'status' => true,
            'message' => 'Dashboard Data',
            'data' => [
                'transaction_borrowed' => $transaction_borrowed,
                'transaction_returned' => $transaction_returned
            ]
        ], 200);
    }
}
