<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Port;
use App\Models\Shipment;
use Illuminate\Http\Request;



use Illuminate\Support\Facades\Auth;

class ShipmentController extends Controller
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

    public function index()
    {
        $title = "Shipments";
        if (Auth::user()->userrole->iduserrole == 1) {
            $_ship = new Shipment;
            $_port = new Port;
            $shipments = $_ship->all();
            $ports = $_port->where('idcountry','=', 109)->get();

            return view(Auth::user()->userrole->path . '/shipments', compact("title", "shipments", "ports"));
        }

        return redirect()->back()->with('fail', 'You do not have access to this page.');
    }


    public function indexUpdate(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Update Shipment";
            $_port = new Port;
            $_ship = new Shipment;
            $shipments = $_ship->all();
            $shipment = $_ship->where('idshipment', '=', $request->id)->first();
            $ports = $_port->where('idcountry','=', 109)->get();

            return view(Auth::user()->userrole->path . '/shipment-update', compact("title", 'shipment', "shipments", "ports"));
        }
    }



    public function addNew(Request $request)
    {

        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            $data = $request->input();
            try {
                $shipment = new Shipment;
                $shipment->type = $data['type'];
                $shipment->name = $data['name'];
                $shipment->departure = $data['departure'];
                $shipment->pol = $data['pol'];
                $shipment->save();


                return redirect('shipments')->with('status', "Added successfully");
            } catch (\Exception $e) {
                return redirect('shipments')->with('failed', "operation failed");
            }
        }
    }

    public function update(Request $request)
    {

        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            $data = $request->input();
            try {
                $shipment = new Shipment;
                $shipment->where("idshipment", '=', $request->idshipment)
                    ->update([
                        'type' => $request->type,
                        'name' => $request->name,
                        'departure' => $request->departure,
                        'pol' => $request->pol,
                        'updated_at' => now()
                    ]);



                return redirect('shipments')->with('status', "Updated successfully");
            } catch (\Exception $e) {
                return redirect('shipments')->with('failed', "operation failed");
            }
        }
    }






}
