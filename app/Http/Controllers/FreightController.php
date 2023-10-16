<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Country;
use App\Models\CurrencyHigh;
use App\Models\Freight;
use App\Models\Order\Order;
use App\Models\Order\OrderPrice;
use App\Models\Stock\Stock;
use App\Models\StockExpense;




use Illuminate\Support\Facades\Auth;


class FreightController extends Controller
{
    //////
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
    public function freightOrder(Request $request)
    {
        $price = 0;
        $stock = new Stock;
        $freight = new Freight;
        $expense = new StockExpense;
        $currency = new CurrencyHigh;
        $_order = new Order;
        $order = $_order->where("idorders", "=", $request->id)->first();
        $cur = $currency->where("idcurrency", "=", 3)->first();
        $rate = $currency->where("idcurrency", "=", $request->currency)->first();
        $details = $stock->where("idstock", "=", $order->idstock)->first();
        $inspection = $expense->where('id', '=', 1)->first();
        $unit = $freight->where([
            ["idport", "=", $request->idport],
            ['shiptype', "=", $request->shipon]
        ])
            ->first();

        $insp = 0;
        $cnf = 0;
        $inspMin = 0;
        $cnfMin = 0;
        if ($request->inspection == 'true'){
            $insp = $order->booking->inspection * $order->booking->jpyrate;
            if($insp <= 0){
                $insp = $inspection->amt;
            }
            $inspMin = $inspection->amt;
        }
        if ($request->tos == 'cnf'){
            $cnf = $order->booking->freight * $order->booking->jpyrate;
            if ($unit) {
                if ($request->shipon == 1) {
                    $cnfMin = ($unit->unit * $cur->jpyrate)  * $details->dimension->m3;
                } else {
                    $cnfMin = $unit->unit * $cur->jpyrate;
                }
            }

        }

        $priceMin = $details->fob / $rate->jpyrate;
        $price = ($order->booking->price * $order->booking->jpyrate) / $rate->jpyrate;
        $request->session()->put("selectedCurrency", $rate->short);
        $data = array(
            "fob" => $price,
            "cnf" => ($cnf / $rate->jpyrate),
            "insp" => ($insp / $rate->jpyrate),
            "fobMin" => $priceMin,
            "cnfMin" => ($cnfMin / $rate->jpyrate),
            "inspMin" => ($inspMin / $rate->jpyrate),
            "cur" => $rate->short
        );
        return json_encode($data);
    }
    public function freight(Request $request)
    {
        $price = 0;
        $stock = new Stock;
        $freight = new Freight;
        $expense = new StockExpense;
        $currency = new CurrencyHigh;
        $cur = $currency->where("idcurrency", "=", 3)->first();
        $rate = $currency->where("idcurrency", "=", $request->currency)->first();
        $details = $stock->where("idstock", "=", $request->id)->first();
        $inspection = $expense->where('id', '=', 1)->first();
        $unit = $freight->where([
            ["idport", "=", $request->idport],
            ['shiptype', "=", $request->shipon]
        ])
            ->first();
        $insp = 0;
        $cnf = 0;
        if ($request->tos == 'cnf') {
            if ($unit) {
                if ($request->shipon == 1) {
                    $cnf = ($unit->unit * $cur->jpyrate)  * $details->dimension->m3;
                } else {
                    $cnf = $unit->unit * $cur->jpyrate;
                }
            }
        }

        if ($request->inspection == 'true') {
            $insp = $inspection->amt;
        }

        $price = $details->fob / $rate->jpyrate;
        $request->session()->put("selectedCurrency", $rate->short);
        $data = array(
            "fob" => $price,
            "cnf" => ($cnf / $rate->jpyrate),
            "insp" => ($insp / $rate->jpyrate),
            "cur" => $rate->short
        );
        return json_encode($data);
    }
}
