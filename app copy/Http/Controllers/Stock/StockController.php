<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ErlandMuchasaj\LaravelFileUploader\FileUploader;

use Intervention\Image\Facades\Image as ResizeImage;

use App\Models\Country;
use App\Models\CurrencyHigh;
use App\Models\Customer;
use App\Models\Stock\Stock;
use App\Models\Stock\Image;
use App\Models\Stock\Auction;
use App\Models\Stock\StockCountry;
use App\Models\Stock\Shipment;
use App\Models\Stock\Documents;
use App\Models\Stock\Inspection;
use App\Models\StockExpense;


use App\Models\Vehicle\Maker;
use App\Models\Vehicle\Models;
use App\Models\Vehicle\Code;
use App\Models\Vehicle\Package;
use App\Models\Vehicle\Dimension;
use App\Models\Vehicle\Vehiclebody;
use App\Models\Vehicle\Fuel;
use App\Models\Vehicle\Transmission;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    //
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
    // stock list views
    public function index()
    {
        $title = "All Stock";
        $stock = new Stock;
        $stocks = $stock->all();

        return view(Auth::user()->userrole->path . '/stock', compact("title", "stocks"));
    }

    public function indexUpdate(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Update Stock";
            $_stock = new Stock;
            $maker = new Maker;
            $model = new Models;
            $_code = new Code;
            $_package = new Package;
            $_dimension = new Dimension;
            $body = new Vehiclebody;
            $_fuel = new Fuel;
            $_trans = new Transmission;
            $country = new Country;

            $stock = $_stock->where('idstock', '=', $request->id)->first();
            $makers = $maker->all();
            $_models = $model->all();
            $_codes = $_code->all();
            $_packages = $_package->all();
            $_dimensions = $_dimension->all();
            $bodies = $body->all();
            $fuels = $_fuel->all();
            $trans = $_trans->all();

            $counties = $country->all();


            return view(Auth::user()->userrole->path . '/stock-update', compact("title", 'stock', "makers", "_models", "_codes", "_packages", "_dimensions", "bodies", "fuels", "trans", "counties"));
        }
    }

    public function indexInspection(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Update Inspection";
            $_stock = new Stock;
            $maker = new Maker;
            $model = new Models;
            $_code = new Code;
            $_package = new Package;
            $_dimension = new Dimension;
            $body = new Vehiclebody;
            $_fuel = new Fuel;
            $_trans = new Transmission;
            $country = new Country;

            $stock = $_stock->where('idstock', '=', $request->id)->first();
            $makers = $maker->all();
            $_models = $model->all();
            $_codes = $_code->all();
            $_packages = $_package->all();
            $_dimensions = $_dimension->all();
            $bodies = $body->all();
            $fuels = $_fuel->all();
            $trans = $_trans->all();

            $counties = $country->all();


            return view(Auth::user()->userrole->path . '/stock-inspection', compact("title", 'stock', "makers", "_models", "_codes", "_packages", "_dimensions", "bodies", "fuels", "trans", "counties"));
        }
    }

    public function publicStock()
    {
        $title = "All Website Stock";
        $stock = new Stock;
        $stocks = $stock->where("website", "=", "1")->get();

        return view(Auth::user()->userrole->path . '/stock', compact("title", "stocks"));
    }
    public function privateStock()
    {
        $title = "All Private Stock";
        $stock = new Stock;
        $stocks = $stock->where("website", "=", "0")->get();

        return view(Auth::user()->userrole->path . '/stock', compact("title", "stocks"));
    }
    public function orignalStock()
    {
        $title = "All Orignal Stock";
        $stock = new Stock;
        $stocks = $stock->where("original", "=", "1")->get();

        return view(Auth::user()->userrole->path . '/stock', compact("title", "stocks"));
    }
    public function fakeStock()
    {
        $title = "All Fake Stock";
        $stock = new Stock;
        $stocks = $stock->where("original", "=", "0")->get();

        return view(Auth::user()->userrole->path . '/stock', compact("title", "stocks"));
    }

    public function orignalPublicStock()
    {
        $title = "All Orignal Public Stock";
        $stock = new Stock;
        $stocks = $stock->where([
            ['original', '1'],
            ['website', '1']
        ])->get();

        return view(Auth::user()->userrole->path . '/stock', compact("title", "stocks"));
    }
    public function orignalPrivateStock()
    {
        $title = "All Orignal Private Stock";
        $stock = new Stock;
        $stocks = $stock->where([
            ['original', '1'],
            ['website', '0']
        ])->get();

        return view(Auth::user()->userrole->path . '/stock', compact("title", "stocks"));
    }
    public function fakePublicStock()
    {
        $title = "All Fake Public Stock";
        $stock = new Stock;
        $stocks = $stock->where([
            ['original', '0'],
            ['website', '1']
        ])->get();

        return view(Auth::user()->userrole->path . '/stock', compact("title", "stocks"));
    }
    public function fakePrivateStock()
    {
        $title = "All Fake Private Stock";
        $stock = new Stock;
        $stocks = $stock->where([
            ['original', '0'],
            ['website', '0']
        ])->get();

        return view(Auth::user()->userrole->path . '/stock', compact("title", "stocks"));
    }



    // view stock add page
    public function addNewIndex()
    {
        if (Auth::user()->userrole->path == 'admin') {

            $title = "Add New Stock";
            $maker = new Maker;
            $makers = $maker->all();
            $model = new Models;
            $_models = $model->all();
            $_code = new Code;
            $_codes = $_code->all();
            $_package = new Package;
            $_packages = $_package->all();
            $_dimension = new Dimension;
            $_dimensions = $_dimension->all();
            $body = new Vehiclebody;
            $bodies = $body->all();
            $_fuel = new Fuel;
            $fuels = $_fuel->all();
            $_trans = new Transmission;
            $trans = $_trans->all();

            $country = new Country;
            $counties = $country->all();

            return view(Auth::user()->userrole->path . '/addNewStock', compact("title", "makers", "_models", "_codes", "_packages", "_dimensions", "bodies", "fuels", "trans", "counties"));
        } else {
            return view('/access-denied');
        }
    }
    // add new stock post and save
    public function addNew(Request $request)
    {

        if (Auth::user()->userrole->path == 'admin') {


            $max_size = (int) ini_get('upload_max_filesize') * 1000;
            $extensions = implode(',', FileUploader::images());
            $request->validate([
                'sheet' => [
                    'required',
                    'file',
                    'image',
                    'mimes:' . $extensions,
                    'max:' . $max_size,
                ],
                'mainImg' => [
                    'required',
                    'file',
                    'image',
                    'mimes:' . $extensions,
                    'max:' . $max_size,
                ],
                'img.*' => [
                    'required',
                    'file',
                    'image',
                    'mimes:' . $extensions,
                    'max:' . $max_size,
                ]
            ]);

            // save auction sheet in public drive
            $sheet = now()->format("YmdHis") . '-auc.' . $request->sheet->extension();
            $request->sheet->move(public_path('auction'), $sheet);

            // save images in public drive
            $files = array();
            $fileName = now()->format("YmdHis") . '-main.' . $request->mainImg->extension();
            $files[] = array('filename' => $fileName);
            $request->mainImg->move(public_path('cars'), $fileName);




            foreach ($request->img as $key => $img) {
                $fileName = now()->format("YmdHis") . "-" . $key . '.' . $img->extension();
                $files[] = array('filename' => $fileName);
                $img->move(public_path('cars'), $fileName);
            }



            /*
            Write Code Here for
            Store $fileName name in DATABASE from HERE
            */


            // save data in database
            $data = $request->input();
            try {
                $stock = new Stock;
                $auction = new Auction;
                $country = new StockCountry;
                $shipment = new Shipment;
                $document = new Documents;
                $insp = new Inspection;

                $stock->idmaker = $data['maker'];
                $stock->idmodel = $data['model'];
                $stock->idcode = $data['code'];
                $stock->idpackage = $data['package'];
                $stock->idvehiclebody = $data['body'];
                $stock->idtransmission = $data['transmission'];
                $stock->iddimension = $data['dimension'];
                $stock->idfuel = $data['fuel'];
                $stock->year = $data['year'];
                $stock->engine = $data['engine'];
                $stock->chassis = $data['chassisid'];
                $stock->interior = $data['interior'];
                $stock->exterior = $data['exterior'];
                $stock->mileage = $data['mileage'];
                $stock->cylinder = $data['cylinder'];
                $stock->condition = $data['condition'];
                $stock->price = $data['buying'];
                $stock->fob = $data['fob'];
                $stock->date = $data['date'];
                $stock->original = $data['original'];
                $stock->website = $data['website'];

                $stock->save();
                // end save stock
                // add images file name
                foreach ($files as $file) {
                    $this->stockImageUpload($stock->id, $file['filename']);
                }
                // end stock images

                // add auction details
                $auction->idstock = $stock->id;
                $auction->place = $data['auction'];
                $auction->lot = $data['lot'];
                $auction->grade = $data['grade'];
                $auction->sheet = $sheet;
                $auction->recycle = $data['recycle'];

                $auction->save();
                // end auction saving

                // save country stock details
                if (!empty($data['country'])) {
                    $country->idstock = $stock->id;
                    $country->idcountry = $data['country'];
                    $country->save();
                }
                // end saving stock  countlry for

                // saving shipment details
                $shipment->idstock = $stock->id;
                $shipment->transportdate = $data['transport'];
                $shipment->pol = $data['pol'];
                $shipment->save();
                // end saving shipment

                // add record for stock documents
                $document->idstock = $stock->id;
                $document->save();
                // end saving documents


                // add inspection details here
                $insp->idstock = $stock->id;
                $insp->save();
                // end inspection details





                return redirect('stock-add')->with('status', "Added successfully");
            } catch (\Exception $e) {
                return redirect('stock-add')->with('failed', "operation failed");
            }
        }
    }
    public function update(Request $request)
    {

        if (Auth::user()->userrole->path == 'admin') {


            $max_size = (int) ini_get('upload_max_filesize') * 1000;
            $extensions = implode(',', FileUploader::images());
            $request->validate([
                'sheet' => [
                    'file',
                    'image',
                    'mimes:' . $extensions,
                    'max:' . $max_size,
                ],
                'mainImg' => [
                    'file',
                    'image',
                    'mimes:' . $extensions,
                    'max:' . $max_size,
                ],
                'img.*' => [
                    'file',
                    'image',
                    'mimes:' . $extensions,
                    'max:' . $max_size,
                ]
            ]);

            // save auction sheet in public drive
            if ($request->sheet) {
                $sheet = now()->format("YmdHis") . '-auc.' . $request->sheet->extension();
                $request->sheet->move(public_path('auction'), $sheet);
            }


            // save images in public drive
            $files = array();
            if ($request->mainImg) {
                $fileName = now()->format("YmdHis") . '-main.' . $request->mainImg->extension();
                $files[] = array('filename' => $fileName);
                $request->mainImg->move(public_path('cars'), $fileName);
            }

            if ($request->img) {
                foreach ($request->img as $key => $img) {
                    $fileName = now()->format("YmdHis") . "-" . $key . '.' . $img->extension();
                    $files[] = array('filename' => $fileName);
                    $img->move(public_path('cars'), $fileName);
                }
            }

            /*
            Write Code Here for
            Store $fileName name in DATABASE from HERE
            */


            // save data in database
            $data = $request->input();
            try {
                $stock = new Stock;
                $auction = new Auction;
                $country = new StockCountry;
                $shipment = new Shipment;
                $document = new Documents;
                $insp = new Inspection;


                $stock->where("idstock", '=', $request->idstock)
                    ->update([
                        'idmaker' => $data['maker'],
                        'idmodel' => $data['model'],
                        'idcode' => $data['code'],
                        'idpackage' => $data['package'],
                        'idvehiclebody' => $data['body'],
                        'idtransmission' => $data['transmission'],
                        'iddimension' => $data['dimension'],
                        'idfuel' => $data['fuel'],
                        'year' => $data['year'],
                        'engine' => $data['engine'],
                        'chassis' => $data['chassisid'],
                        'interior' => $data['interior'],
                        'exterior' => $data['exterior'],
                        'mileage' => $data['mileage'],
                        'cylinder' => $data['cylinder'],
                        'condition' => $data['condition'],
                        'price' => $data['buying'],
                        'fob' => $data['fob'],
                        'date' => $data['date'],
                        'original' => $data['original'],
                        'website' => $data['website'],
                        'updated_at' => now()
                    ]);
                // end save stock
                // add images file name

                // foreach ($files as $file) {
                //     $this->stockImageUpload($stock->id, $file['filename']);
                // }

                // end stock images

                // add auction details

                $auction->where("idstock", '=', $request->idstock)
                    ->update([
                        'place' => $data['auction'],
                        'lot' => $data['lot'],
                        'grade' => $data['grade'],
                        'recycle' => $data['recycle'],
                        'updated_at' => now()
                    ]);

                if ($request->sheet) {
                    $auction->where("idstock", '=', $request->idstock)
                        ->update([
                            'sheet' => $sheet,
                            'updated_at' => now()
                        ]);
                }
                // end auction saving

                // save country stock details
                if (!empty($data['country'])) {
                    $country->where("idstock", '=', $request->idstock)
                        ->update([
                            'idcountry' => $data['country'],
                            'updated_at' => now()
                        ]);
                }
                // end saving stock  countlry for

                // saving shipment details
                $shipment->where("idstock", '=', $request->idstock)
                    ->update([
                        'transportdate' => $data['transport'],
                        'pol' => $data['pol'],
                        'updated_at' => now()
                    ]);
                // end saving shipment


                return redirect('stock')->with('status', "Updated successfully");
            } catch (\Exception $e) {
                return redirect('stock')->with('failed', "operation failed");
            }
        }
    }

    public function inspectionUpdate(Request $request)
    {
        // return $request;
        if (Auth::user()->userrole->path == 'admin') {

            $max_size = (int) ini_get('upload_max_filesize') * 1000;
            $extensions = implode(',', FileUploader::images());

            $cert = '';
            if ($request->file) {
                $cert = now()->format("YmdHis") . '-insp-cert.' . $request->file->extension();
                $request->file->move(public_path('inspection'), $cert);
            }

            // save data in database
            $data = $request->input();
            try {
            $stock = new Stock;
            $insp = new Inspection;



            $insp->where("idstock", '=', $request->idstock)
                ->update([
                    'status' => $data['status'],
                    'file' => $data['cert'],
                    'hide' => $data['hide'],
                    'expecteddate' => $data['expecteddate'],
                    'remarks' => $data['remarks'],
                    'updated_at' => now()
                ]);
            if ($data['hide']) {
                $insp->where("idstock", '=', $request->idstock)
                    ->update([
                        'hide' => $data['hide'],
                        'updated_at' => now()
                    ]);
            }
            if ($request->file) {
                $insp->where("idstock", '=', $request->idstock)
                    ->update([
                        'file' => $cert,
                        'updated_at' => now()
                    ]);
            }


                return redirect()->back()->with('status', "Updated successfully");
            } catch (\Exception $e) {
                return redirect()->back()->with('failed', "operation failed");
            }
        }
    }

    private function stockImageUpload($id, $img)
    {
        $image = new Image;
        $image->idstock = $id;
        $image->image = $img;
        $image->save();
    }


    // stock details
    public function searchStock(Request $request)
    {
        $stock = new Stock;
        $details = $stock->where("idstock", "=", $request->id)->first();
        $url = "/stock-details-view/" . str_replace(" ", "-", strtolower($details->maker->name)) . "/" . str_replace(" ", "-", strtolower($details->model->name)) . "/" . $details->idstock;
        return redirect($url);
    }

    public function stockDetails(Request $request)
    {
        $stock = new Stock;
        $country = new Country;
        $countries = $country->get();
        $details = $stock->where("idstock", "=", $request->id)->first();
        if (str_replace(" ", "-", strtolower($details->maker->name)) != $request->make || str_replace(" ", "-", strtolower($details->model->name))  != $request->model) {
            return redirect("/stock-details-view/" . $request->id);
        }

        if (Auth::user()->userrole->iduserrole == 1) {
            $maker = new Maker;
            $makers = $maker->get();
            $currency = new CurrencyHigh;
            $currencies = $currency->get();
            $expense = new StockExpense;
            $customer = new Customer;

            $customers = $customer->all();

            $stockExpense = $expense->where('id', '=', 1)->first();
            $inspection = $stockExpense->amt;

            $title = ucwords(str_replace("-", " ", $request->make) . " " . str_replace("-", " ", $request->model));

            $stockId = $request->id;

            $request->session()->put("selectedCurrency", 'JPY');

            return view(Auth::user()->userrole->path . '/addNewOrder', compact("title", "stockId", "details", "makers", "countries", "currencies", "inspection", "customers"));
        }
    }
}
