<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Wallet;

use App\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WalletTransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function addBalance(Request $request)
    {
        $wallet = Wallet::where('user_id', Auth::user()->id)->first();
        try {
            DB::beginTransaction();
            $transaction = new WalletTransaction();
            $transaction->wallet_id = $wallet->id;
            $transaction->amount = $request->amount;
            $transaction->payment_method = $request->payment_method;
            $transaction->transaction_type = 'income';
            $transaction->save();

            $wallet->balance += $transaction->amount;
            $wallet->save(); 

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $transaction
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 400);
        }
    }

    public function minBalance(Request $request) {
        $wallet = Wallet::where('user_id', Auth::user()->id)->first();
        try {
            DB::beginTransaction();
            $transaction = new WalletTransaction();
            $transaction->wallet_id = $wallet->id;
            $transaction->amount = $request->amount;
            $transaction->payment_method = $request->payment_method;
            $transaction->transaction_type = 'outcome';
            $transaction->save();

            if ($wallet->balance >= $request->amount) {
                $wallet->balance -= $transaction->amount;
                $wallet->save();
            } else {
                 return response()->json([
                'success' => false,
                'message' => 'Balance anda tidak cukup'
                 ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $transaction
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 400);
        }
    }


}