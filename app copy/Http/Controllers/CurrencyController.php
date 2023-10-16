<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Currency;

use App\Models\CurrencyHigh;

use Illuminate\Support\Facades\Auth;

class CurrencyController extends Controller
{
    //
    //

    public function __construct()
    {
        // $this->middleware('auth');
    }


    public function index()
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Supported Currencies";
            $currency = new Currency;
            $currencies = $currency->all();
            return view(Auth::user()->userrole->path . '/currency', compact("title", "currencies"));
        }
    }

    public function indexHigh()
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Supported High Currencies";
            $currency = new CurrencyHigh;
            $currencies = $currency->all();
            return view(Auth::user()->userrole->path . '/currency-high', compact("title", "currencies"));
        }
    }

    public function lowUpdate(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Update High Currency";
            $currency = new Currency;
            $curr = $currency->where('idcurrency', '=', $request->id)->first();
            $currencies = $currency->all();

            return view(Auth::user()->userrole->path . '/currency-update', compact("title", "curr", "currencies"));
        }
    }
    public function highUpdate(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Update High Currency";
            $currency = new CurrencyHigh;
            $curr = $currency->where('idcurrency', '=', $request->id)->first();
            $currencies = $currency->all();

            return view(Auth::user()->userrole->path . '/currency-high-update', compact("title", "curr", "currencies"));
        }
    }





    public function addNew(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            $data = $request->input();
            try {
                $currency = new Currency;
                $currency->name = $data['name'];
                $currency->symbol = $data['symbol'];
                $currency->short = $data['short'];
                $currency->jpyrate = $data['rate'];
                $currency->save();



                return redirect('currency')->with('status', "Added successfully");
            } catch (\Exception $e) {
                return redirect('currency')->with('failed', "operation failed");
            }
        }
    }

    public function addNewHigh(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            $data = $request->input();
            try {
                $currency = new CurrencyHigh;
                $currency->name = $data['name'];
                $currency->symbol = $data['symbol'];
                $currency->short = $data['short'];
                $currency->jpyrate = $data['rate'];
                $currency->save();



                return redirect('currency-high')->with('status', "Added successfully");
            } catch (\Exception $e) {
                return redirect('currency-high')->with('failed', "operation failed");
            }
        }
    }

    public function updateLow(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            try {
                $currency = new Currency;
                $currency->where("idcurrency", '=', $request->idcurrency)
                    ->update([
                        'name' => $request->name,
                        'symbol' => $request->symbol,
                        'short' => $request->short,
                        'jpyrate' => $request->rate,
                        'updated_at' => now()
                    ]);

                return redirect('currency')->with('status', "Update successfully");
            } catch (\Exception $e) {
                return redirect('currency')->with('failed', "operation failed");
            }
        }
    }

    public function updateHigh(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            try {
                $currency = new CurrencyHigh;
                $currency->where("idcurrency", '=', $request->idcurrency)
                    ->update([
                        'name' => $request->name,
                        'symbol' => $request->symbol,
                        'short' => $request->short,
                        'jpyrate' => $request->rate,
                        'updated_at' => now()
                    ]);

                return redirect('currency-high')->with('status', "Update successfully");
            } catch (\Exception $e) {
                return redirect('currency-high')->with('failed', "operation failed");
            }
        }
    }
}
