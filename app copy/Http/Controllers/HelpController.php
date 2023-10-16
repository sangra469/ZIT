<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;


use App\Models\Vehicle\Maker;
use App\Models\Stock\Stock;

use App\Models\Country;
use App\Models\Currency;
use App\Models\CurrencyHigh;
use App\Models\StockExpense;
use App\Models\Freight;
use App\Models\Vehicle\Fuel;
use App\Models\Vehicle\Models;
use App\Models\Vehicle\Steering;
use App\Models\Vehicle\Transmission;
use App\Models\Vehicle\Vehiclebody;

class HelpController extends Controller
{
    //// constructor
    public function __construct()
    {
        $currency = new Currency;
        $cur = $currency->where("idcurrency", "=", 1)->first();
        session()->put('typeCurrency', $cur->idcurrency);
        session()->put('rate', $cur->jpyrate);
        session()->put('shortCurrency', $cur->short);
        session()->put('symbol', $cur->symbol);
    }


    public function helpHowToBuy(){
        $title = "About Logistics ZI Trading";
        $maker = new Maker;
        $stock = new Stock;
        $currency = new Currency;
        $body = new Vehiclebody;
        $transmission = new Transmission;
        $steering = new Steering;
        $fuel = new Fuel;

        $makers = $maker->get();
        $stocks = $stock->orderBy("idstock", "DESC")->get();
        $currencies = $currency->get();


        $fuels = $fuel->all();
        $steers = $steering->all();
        $transmissions = $transmission->all();
        $bodies = $body->all();

        return view('how-to-buy', compact("title", "makers", "bodies", "transmissions", "steers", "fuels", "stocks", "currencies"));
    }

    public function helpHowToRequestACar(){
        $title = "About Logistics ZI Trading";
        $maker = new Maker;
        $stock = new Stock;
        $currency = new Currency;
        $body = new Vehiclebody;
        $transmission = new Transmission;
        $steering = new Steering;
        $fuel = new Fuel;

        $makers = $maker->get();
        $stocks = $stock->orderBy("idstock", "DESC")->get();
        $currencies = $currency->get();


        $fuels = $fuel->all();
        $steers = $steering->all();
        $transmissions = $transmission->all();
        $bodies = $body->all();

        return view('how-to-request-car', compact("title", "makers", "bodies", "transmissions", "steers", "fuels", "stocks", "currencies"));
    }

    public function helpHowToShipContainer(){
        $title = "About Logistics ZI Trading";
        $maker = new Maker;
        $stock = new Stock;
        $currency = new Currency;
        $body = new Vehiclebody;
        $transmission = new Transmission;
        $steering = new Steering;
        $fuel = new Fuel;

        $makers = $maker->get();
        $stocks = $stock->orderBy("idstock", "DESC")->get();
        $currencies = $currency->get();


        $fuels = $fuel->all();
        $steers = $steering->all();
        $transmissions = $transmission->all();
        $bodies = $body->all();

        return view('how-to-ship-container', compact("title", "makers", "bodies", "transmissions", "steers", "fuels", "stocks", "currencies"));
    }


    public function contact(){
        $title = "Contact Us ZI Trading";
        $maker = new Maker;
        $stock = new Stock;
        $currency = new Currency;
        $body = new Vehiclebody;
        $transmission = new Transmission;
        $steering = new Steering;
        $fuel = new Fuel;

        $makers = $maker->get();
        $stocks = $stock->orderBy("idstock", "DESC")->get();
        $currencies = $currency->get();


        $fuels = $fuel->all();
        $steers = $steering->all();
        $transmissions = $transmission->all();
        $bodies = $body->all();

        return view('contact-us', compact("title", "makers", "bodies", "transmissions", "steers", "fuels", "stocks", "currencies"));
    }
    public function vision(){
        $title = "Contact Us ZI Trading";
        $maker = new Maker;
        $stock = new Stock;
        $currency = new Currency;
        $body = new Vehiclebody;
        $transmission = new Transmission;
        $steering = new Steering;
        $fuel = new Fuel;

        $makers = $maker->get();
        $stocks = $stock->orderBy("idstock", "DESC")->get();
        $currencies = $currency->get();


        $fuels = $fuel->all();
        $steers = $steering->all();
        $transmissions = $transmission->all();
        $bodies = $body->all();

        return view('vision', compact("title", "makers", "bodies", "transmissions", "steers", "fuels", "stocks", "currencies"));
    }






}
