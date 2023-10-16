<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



use App\Models\Country;

use App\Models\Freight;


use Illuminate\Support\Facades\Auth;

class FreightAddController extends Controller
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
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "All Port Freight";

            $country = new Country;
            $countries = $country->all();

            $freight = new Freight;
            $freights = $freight->all();

            return view(Auth::user()->userrole->path . '/freight', compact("title", "freights", "countries"));
        }
    }

    public function indexUpdate(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Update Port Freight";

            $country = new Country;
            $countries = $country->all();

            $_freight = new Freight;
            $freight = $_freight->where('id', '=', $request->id)->first();
            $freights = $_freight->all();

            return view(Auth::user()->userrole->path . '/freight-update', compact("title", "freight", "freights", "countries"));
        }
    }


    public function addNew(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            try {
                $freight = new Freight;
                $freight->idport = $request->port;
                $freight->shiptype = $request->ship;
                $freight->unit = $request->unit;
                $freight->save();



                return redirect('freight')->with('status', "Added successfully");
            } catch (\Exception $e) {
                return redirect('freight')->with('failed', "operation failed");
            }

            return redirect('freight')->with('failed', "operation failed");;
        }
    }

    public function update(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            try {
                $freight = new Freight;
                $freight->where("id", '=', $request->id)
                    ->update([
                        'idport' => $request->port,
                        'shiptype' => $request->ship,
                        'unit' => $request->unit,
                        'updated_at' => now()
                    ]);



                return redirect('freight')->with('status', "Added successfully");
            } catch (\Exception $e) {
                return redirect('freight')->with('failed', "operation failed");
            }

            return redirect('freight')->with('failed', "operation failed");;
        }
    }
}
