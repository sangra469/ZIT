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
use App\Models\Stock\StockCountry;
use App\Models\Vehicle\Fuel;
use App\Models\Vehicle\Models;
use App\Models\Vehicle\Steering;
use App\Models\Vehicle\Transmission;
use App\Models\Vehicle\Vehiclebody;

class WebsiteController extends Controller
{
    //
    public $makers ;
    public $stocks ;
    public $currencies ;


    public $fuels ;
    public $steers ;
    public $transmissions ;
    public $bodies ;

    // constructor
    public function __construct()
    {
        $currency = new Currency;
        $cur = $currency->where("idcurrency", "=", 1)->first();
        session()->put('typeCurrency', $cur->idcurrency);
        session()->put('rate', $cur->jpyrate);
        session()->put('shortCurrency', $cur->short);
        session()->put('symbol', $cur->symbol);


        $maker = new Maker;
        $stock = new Stock;
        $currency = new Currency;
        $body = new Vehiclebody;
        $transmission = new Transmission;
        $steering = new Steering;
        $fuel = new Fuel;

        $this->makers = $maker->all();
        $this->stocks = $stock->orderBy("idstock", "DESC")->limit(12)->get();
        $this->currencies = $currency->get();


        $this->fuels = $fuel->all();
        $this->steers = $steering->all();
        $this->transmissions = $transmission->all();
        $this->bodies = $body->all();

    }

    // home page
    public function index()
    {
        $title = "ZI Trading";

        return view('index', array_merge( compact("title"),  ["makers" => $this->makers, "bodies" => $this->bodies, "transmissions" => $this->transmissions, "steers" => $this->steers, "fuels" => $this->fuels, "stocks" => $this->stocks, "currencies" => $this->currencies ]) );
    }
    public function testimonials(){
        $title = "Testimonials ZI Trading";

        return view('testimonials', array_merge( compact("title"),  ["makers" => $this->makers, "bodies" => $this->bodies, "transmissions" => $this->transmissions, "steers" => $this->steers, "fuels" => $this->fuels, "stocks" => $this->stocks, "currencies" => $this->currencies ]) );
    }
    public function aboutLogistic(){
        $title = "About Logistics ZI Trading";

        return view('about-logistic', array_merge( compact("title"),  ["makers" => $this->makers, "bodies" => $this->bodies, "transmissions" => $this->transmissions, "steers" => $this->steers, "fuels" => $this->fuels, "stocks" => $this->stocks, "currencies" => $this->currencies ]) );
    }
    public function about()
    {
        $title = "About ZI Trading";

        return view('about', array_merge( compact("title"),  ["makers" => $this->makers, "bodies" => $this->bodies, "transmissions" => $this->transmissions, "steers" => $this->steers, "fuels" => $this->fuels, "stocks" => $this->stocks, "currencies" => $this->currencies ]) );
    }
    public function aboutAuction()
    {
        $title = "About ZI Trading";

        return view('about-auction', array_merge( compact("title"),  ["makers" => $this->makers, "bodies" => $this->bodies, "transmissions" => $this->transmissions, "steers" => $this->steers, "fuels" => $this->fuels, "stocks" => $this->stocks, "currencies" => $this->currencies ]) );
    }
    public function aboutAuctionBuying()
    {
        $title = "About ZI Trading";
        return view('about-auction-buying', array_merge( compact("title"),  ["makers" => $this->makers, "bodies" => $this->bodies, "transmissions" => $this->transmissions, "steers" => $this->steers, "fuels" => $this->fuels, "stocks" => $this->stocks, "currencies" => $this->currencies ]) );
    }
    public function aboutAuctionSaving()
    {
        $title = "About ZI Trading";
        return view('about-auction-saving', array_merge( compact("title"),  ["makers" => $this->makers, "bodies" => $this->bodies, "transmissions" => $this->transmissions, "steers" => $this->steers, "fuels" => $this->fuels, "stocks" => $this->stocks, "currencies" => $this->currencies ]) );
    }

    public function auctionVehicle()
    {

        $contents = file_get_contents("http://78.46.90.228/xml/json?code=DvemR43s&sql=select%20MARKA_ID,MARKA_NAME%20from%20main%20%20group%20by%20MARKA_ID%20order%20by%20MARKA_NAME");
        $contents = utf8_encode($contents);
        $amakers = json_decode($contents, true);
        // return $amakers;
        $title = "ZI Trading Auction";
        $maker = new Maker;
        $stock = new Stock;
        $currency = new Currency;
        $body = new Vehiclebody;
        $transmission = new Transmission;
        $steering = new Steering;
        $fuel = new Fuel;

        $makers = $maker->all();
        $stocks = $stock->orderBy("idstock", "DESC")->get();
        $currencies = $currency->get();


        $fuels = $fuel->all();
        $steers = $steering->all();
        $transmissions = $transmission->all();
        $bodies = $body->all();

        return view('auction-vehicle', array_merge( compact("title", "amakers"),  ["makers" => $this->makers, "bodies" => $this->bodies, "transmissions" => $this->transmissions, "steers" => $this->steers, "fuels" => $this->fuels, "stocks" => $this->stocks, "currencies" => $this->currencies ]) );
    }

    public function auctionVehicleModel(Request $request)
    {
        $contents = file_get_contents("http://78.46.90.228/xml/json?code=DvemR43s&sql=select%20MARKA_NAME,MODEL_ID,MODEL_NAME%20from%20main%20where%20MARKA_ID=%27".$request->idmaker."%27%20group%20by%20MODEL_ID%20order%20by%20MODEL_ID");
        $contents = utf8_encode($contents);
        $amodel = json_decode($contents, true);

        $response = collect([]);
        $response->push([
            'id' => "",
            'text' => "Select " . ($amodel[0]['MARKA_NAME']) . " Model",
        ]);
        foreach ($amodel as $_model) {
            $response->push([
                'id' => $_model['MODEL_ID'],
                'text' => $_model['MODEL_NAME'],
            ]);
        }

        return response()->json($response);
    }
    public function auctionVehicleCode(Request $request)
    {
        $contents = file_get_contents("http://78.46.90.228/xml/json?code=DvemR43s&sql=select%20MODEL_NAME,KUZOV%20from%20main%20where%20MODEL_ID=%27".$request->idmodel."%27%20group%20by%20KUZOV%20order%20by%20KUZOV");
        $contents = utf8_encode($contents);
        $amodel = json_decode($contents, true);

        $response = collect([]);
        $response->push([
            'id' => "",
            'text' => "Select " . ($amodel[0]['MODEL_NAME']) . " Code",
        ]);
        foreach ($amodel as $_model) {
            $response->push([
                'id' => $_model['KUZOV'],
                'text' => $_model['KUZOV'],
            ]);
        }

        return response()->json($response);
    }
    public function auctionVehicleColor(Request $request)
    {
        $contents = file_get_contents("http://78.46.90.228/xml/json?code=DvemR43s&sql=select%20MODEL_NAME,COLOR%20from%20main%20where%20MODEL_ID=%27".$request->idmodel."%27%20group%20by%20COLOR%20order%20by%20COLOR");
        $contents = utf8_encode($contents);
        $amodel = json_decode($contents, true);

        $response = collect([]);
        $response->push([
            'id' => "",
            'text' => "Select " . ($amodel[0]['MODEL_NAME']) . " Color",
        ]);
        foreach ($amodel as $_model) {
            $response->push([
                'id' => $_model['COLOR'],
                'text' => $_model['COLOR'],
            ]);
        }

        return response()->json($response);
    }
    public function auctionVehicleGrade(Request $request)
    {
        $contents = file_get_contents("http://78.46.90.228/xml/json?code=DvemR43s&sql=select%20MODEL_NAME,RATE%20from%20main%20where%20MODEL_ID=%27".$request->idmodel."%27%20group%20by%20RATE%20order%20by%20RATE");
        $contents = utf8_encode($contents);
        $amodel = json_decode($contents, true);

        $response = collect([]);
        $response->push([
            'id' => "",
            'text' => "Select " . ($amodel[0]['MODEL_NAME']) . " Grade",
        ]);
        foreach ($amodel as $_model) {
            $response->push([
                'id' => $_model['RATE'],
                'text' => $_model['RATE'],
            ]);
        }

        return response()->json($response);
    }

    public function auctionVehicleModelYearMileageCc(Request $request)
    {
        $contents = file_get_contents("http://78.46.90.228/xml/json?code=DvemR43s&sql=select%20YEAR%20from%20main%20where%20MODEL_ID=%27".$request->idmodel."%27%20group%20by%20YEAR%20order%20by%20YEAR");
        $contents = utf8_encode($contents);
        $amodel = json_decode($contents, true);

        $contents = file_get_contents("http://78.46.90.228/xml/json?code=DvemR43s&sql=select%20ENG_V%20from%20main%20where%20MODEL_ID=%27".$request->idmodel."%27%20group%20by%20ENG_V%20order%20by%20ENG_V");
        $contents = utf8_encode($contents);
        $acc = json_decode($contents, true);

        $contents = file_get_contents("http://78.46.90.228/xml/json?code=NbfrUy3_dsK&sql=select%20MILEAGE%20from%20main%20where%20MODEL_ID=%27".$request->idmodel."%27%20group%20by%20MILEAGE%20order%20by%20MILEAGE");
        $contents = utf8_encode($contents);
        $amileage = json_decode($contents, true);


        $response = collect([]);
        $response->push([
            'min' => min($amodel),
            'max' => max($amodel),
        ]);
        $response->push([
            'min' => min($acc),
            'max' => max($acc),
        ]);
        $response->push([
            'min' => min($amileage),
            'max' => max($amileage),
        ]);

        return response()->json($response);
    }


    public function countriesVehicles()
    {
        $title = "Search By Country";
        $page = "countries";
        return view('country',  array_merge( compact("title", "page"),  ["makers" => $this->makers, "bodies" => $this->bodies, "transmissions" => $this->transmissions, "steers" => $this->steers, "fuels" => $this->fuels, "stocks" => $this->stocks, "currencies" => $this->currencies ]) );
    }
    public function countryVehicles(string $country)
    {
        $countryName = str_replace('-', ' ', strtolower($country));
        $title = "Search " . strtoupper($countryName);
        $stockCountry = new StockCountry;
        $stocks = $stockCountry->whereHas("country", function ($query) use ($countryName) {
                                            if (!empty($countryName)) {
                                                return $query->where("name", "like", "%{$countryName}%");
                                            }
                                        })
                                        ->orderBy("idstock", "DESC")
                                        ->get();


        $page = $country;
        return view('country',   array_merge( compact("title", "page", "countryName", "stocks" ),  ["makers" => $this->makers, "bodies" => $this->bodies, "transmissions" => $this->transmissions, "steers" => $this->steers, "fuels" => $this->fuels, "currencies" => $this->currencies ]) );
    }


    // search makers
    public function make()
    {
        $title = "Search By Brands";
        $page = "brands";
        return view('catalog',  array_merge( compact("title", "page"),  ["makers" => $this->makers, "bodies" => $this->bodies, "transmissions" => $this->transmissions, "steers" => $this->steers, "fuels" => $this->fuels, "stocks" => $this->stocks, "currencies" => $this->currencies ]) );
    }


    // Search By Maker

    public function searchMakerModel(string $make)
    {
        $title = "Search " . $make;
        $makerName = str_replace('-', ' ', strtolower($make));
        $maker = new Maker;
        $makers = $maker->all();
        $makeId = $maker->where("name", "like", "%{$makerName}%")->first();
        $stock = new Stock;
        $stocks = $stock->where("idmaker", "=", $makeId->idmaker)->orderBy("idstock", "DESC")->get();


        $page = "make";
        return view('catalog',   array_merge( compact("title", "page", "stocks" ),  ["makers" => $this->makers, "bodies" => $this->bodies, "transmissions" => $this->transmissions, "steers" => $this->steers, "fuels" => $this->fuels, "currencies" => $this->currencies ]) );
    }
    public function vehicleSearch(Request $request)
    {
        // return $request;
        $year = explode(",", $request->year);
        $mileage = explode(",", $request->mileage);
        $cc = explode(",", $request->cc);


        $fMaker = $request->makes;
        $fModel = $request->model;
        $fBody = $request->body;
        $fTransmission = $request->transmission;
        $fSteer = $request->steer;
        $fFuel = $request->fuel;

        $fMinYear = $year[0];
        $fMaxYear = $year[1];
        $fMinMileage = intval($mileage[0]) * 1000;
        $fMaxMileage = intval($mileage[1]) * 1000;
        $fMinCC = $cc[0];
        $fMaxCC = $cc[1];



        $maker = new Maker;
        $model = new Models;
        $stock = new Stock;
        $body = new Vehiclebody;
        $transmission = new Transmission;
        $steering = new Steering;
        $fuel = new Fuel;

        $fuels = $fuel->all();
        $steers = $steering->all();
        $transmissions = $transmission->all();
        $bodies = $body->all();
        $makers = $maker->all();
        $models = $model->where("idmaker", "=", $request->makes)->get();


        $makerName = $maker->where("idmaker", "=", $request->makes)->first();
        $title = "Search " . $makerName->name;

        $stocks = $stock->where(function ($query) use ($fMaker) {
            if (!empty($fMaker)) {
                return $query->where("idmaker", "=", $fMaker);
            }
        })
            ->where(function ($query) use ($fModel) {
                if (!empty($fModel)) {
                    return $query->where("idmodel", "=", $fModel);
                }
            })
            ->where(function ($query) use ($fMinYear) {
                if (!empty($fMinYear)) {
                    return $query->where("year", ">=", $fMinYear);
                }
            })
            ->where(function ($query) use ($fMaxYear) {
                if (!empty($fMaxYear)) {
                    return $query->where("year", "<=", $fMaxYear);
                }
            })
            ->where(function ($query) use ($fMinMileage) {
                if (!empty($fMinMileage)) {
                    return $query->where("mileage", ">=", $fMinMileage);
                }
            })
            ->where(function ($query) use ($fMaxMileage) {
                if (!empty($fMaxMileage)) {
                    return $query->where("mileage", "<=", $fMaxMileage);
                }
            })
            ->where(function ($query) use ($fMinCC) {
                if (!empty($fMinCC)) {
                    return $query->where("engine", ">=", $fMinCC);
                }
            })
            ->where(function ($query) use ($fMaxCC) {
                if (!empty($fMaxCC)) {
                    return $query->where("engine", "<=", $fMaxCC);
                }
            })
            ->where(function ($query) use ($fBody) {
                if (!empty($fBody)) {
                    return $query->where("idvehiclebody", "=", $fBody);
                }
            })
            ->where(function ($query) use ($fTransmission) {
                if (!empty($fTransmission)) {
                    return $query->where("idtransmission", "=", $fTransmission);
                }
            })
            ->where(function ($query) use ($fFuel) {
                if (!empty($fFuel)) {
                    return $query->where("idfuel", "=", $fFuel);
                }
            })
            ->whereHas("code", function ($query) use ($fSteer) {
                if (!empty($fSteer)) {
                    return $query->where("idsteering", "=", $fSteer);
                }
            })
            ->orderBy("idstock", "DESC")
            ->get();
        $currency = new Currency;
        $currencies = $currency->get();


        return view('search', compact("title", "makers", "models", "bodies", "transmissions", "steers", "fuels", "stocks", "currencies", "request"));
    }


    public function searchStock(Request $request)
    {
        $stock = new Stock;
        $details = $stock->where("idstock", "=", $request->id)->first();
        $url = "/stock-details/" . str_replace(" ", "-", strtolower($details->maker->name)) . "/" . str_replace(" ", "-", strtolower($details->model->name)) . "/" . $details->idstock;
        return redirect($url);
    }

    public function stockDetails(Request $request)
    {
        $stock = new Stock;
        $country = new Country;
        $countries = $country->get();
        $details = $stock->where("idstock", "=", $request->id)->first();
        if (str_replace(" ", "-", strtolower($details->maker->name)) != $request->make || str_replace(" ", "-", strtolower($details->model->name))  != $request->model) {
            return redirect("/stock-details/" . $request->id);
        }

        $maker = new Maker;
        $makers = $maker->all();
        $currency = new CurrencyHigh;
        $currencies = $currency->get();
        $expense = new StockExpense;

        $stockExpense = $expense->where('id', '=', 1)->first();
        $inspection = $stockExpense->amt;

        $title = ucwords(str_replace("-", " ", $request->make) . " " . str_replace("-", " ", $request->model));

        $stockId = $request->id;

        return view("/stock-details", compact("title", "stockId", "details", "makers", "countries", "currencies", "inspection"));
    }


    public function changeCurrency(Request $request)
    {
        $currency = new CurrencyHigh;
        $cur = $currency->where("idcurrency", "=", $request->id)->first();
        $request->session()->put('typeCurrency', $cur->idcurrency);
        $request->session()->put('rate', $cur->jpyrate);
        $request->session()->put('shortCurrency', $cur->short);
        $request->session()->put('symbol', $cur->symbol);
        return 200;
    }


    public function freight(Request $request)
    {
        $price = 0;
        $stock = new Stock;
        $freight = new Freight;
        $expense = new StockExpense;
        $currency = new CurrencyHigh;
        $cur = $currency->where("idcurrency", "=", 3)->first();
        $details = $stock->where("idstock", "=", $request->id)->first();
        $inspection = $expense->where('id', '=', 1)->first();
        $unit = $freight->where([
            ["idport", "=", $request->idport],
            ['shiptype', "=", $request->shipon]
        ])
            ->first();
        if ($request->tos == 'fob') {
            $price = $details->fob;
        } else if ($unit) {
            if ($request->shipon == 1) {
                $price = $details->fob + (($unit->unit * $cur->jpyrate)  * $details->dimension->m3);
            } else {
                $price = $details->fob +  ($unit->unit * $cur->jpyrate);
            }
        } else {
            $price = $details->fob;
        }

        if ($request->inspection == 'true') {
            $price += $inspection->amt;
        }

        $price = $price / session()->get('rate');

        return number_format($price);
    }



}
