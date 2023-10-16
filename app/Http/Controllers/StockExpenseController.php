<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



use App\Models\StockExpense;


use Illuminate\Support\Facades\Auth;


class StockExpenseController extends Controller
{
    ////
    /**
     * Create a new controller instance.
     *
     * @return void`
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $title = "All Stock Expenses";

        $expense = new StockExpense;
        $expenses = $expense->all();

        return view(Auth::user()->userrole->path . '/stock-expense', compact("title", "expenses"));
    }


    public function indexUpdate(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Update Stock Expenses";

            $_expense = new StockExpense;
            $expenses = $_expense->all();
            $expense = $_expense->where('id', '=', $request->id)->first();

            return view(Auth::user()->userrole->path . '/stock-expense-update', compact("title", 'expense', "expenses"));
        }
    }


    public function addNew(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            try {
                $expense = new StockExpense;
                $expense->name = $request->expense;
                $expense->amt = $request->amount;
                $expense->save();



                return redirect('expense')->with('status', "Added successfully");
            } catch (\Exception $e) {
                return redirect('expense')->with('failed', "operation failed");
            }
        }
    }
    public function update(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            try {
                $expense = new StockExpense;
                $expense->where("id", '=', $request->id)
                    ->update([
                        'name' => $request->expense,
                        'amt' => $request->amount,
                        'updated_at' => now()
                    ]);



                return redirect('expense')->with('status', "Added successfully");
            } catch (\Exception $e) {
                return redirect('expense')->with('failed', "operation failed");
            }
        }
    }


}
