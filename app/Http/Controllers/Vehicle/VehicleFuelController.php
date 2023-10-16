<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle\Fuel;

use Illuminate\Support\Facades\Auth;


class VehicleFuelController extends Controller
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
    public function index()
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Vehicle Fuel Type";
            $_fuel = new Fuel;
            $fuels = $_fuel->all();
            return view(Auth::user()->userrole->path . '/fuelType', compact("title", "fuels"));
        }
    }
    public function indexUpdate(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Update Fuel Type";
            $_fuel = new Fuel;
            $fuels = $_fuel->all();
            $fuel = $_fuel->where('idfuel', '=', $request->id)->first();
            return view(Auth::user()->userrole->path . '/fuel-type-update', compact("title", "fuel", "fuels"));
        }
    }



    public function addNew(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            $data = $request->input();
            try {
                $t = new Fuel;
                $t->name = $data['name'];
                $t->short = $data['short'];
                $t->save();



                return redirect('vehicle-fuel')->with('status', "Added successfully");
            } catch (\Exception $e) {
                return redirect('vehicle-fuel')->with('failed', "operation failed");
            }
        }
    }

    public function update(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            try {
                $t = new Fuel;
                $t->where("idfuel", '=', $request->idfuel)
                    ->update([
                        'name' => $request->name,
                        'short' => $request->short,
                        'updated_at' => now()
                    ]);


                return redirect('vehicle-fuel')->with('status', "Updated successfully");
            } catch (\Exception $e) {
                return redirect('vehicle-fuel')->with('failed', "operation failed");
            }
        }
    }
}
