<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Country;
use App\Models\Port;

use Illuminate\Support\Facades\Auth;


class PortController extends Controller
{
    //

    public function __construct()
    {
        // $this->middleware('auth');
    }


    public function index()
    {
        $title = "Country Ports";
        $country = new Country;
        $countries = $country->all();
        $port = new Port;
        $ports = $port->all();
        return view(Auth::user()->userrole->path . '/ports', compact("title", "countries", "ports"));
    }


    public function indexUpdate(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Update Port";
            $country = new Country;
            $countries = $country->all();
            $_port = new Port;
            $ports = $_port->all();
            $port = $_port->where('idport', '=', $request->id)->first();

            return view(Auth::user()->userrole->path . '/port-update', compact("title", 'port', "countries", "ports"));
        }
    }



    public function addNew(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            $data = $request->input();
            try {
                $port = new Port;
                $port->idcountry = $data['country'];
                $port->name = $data['port'];
                $port->save();



                return redirect('ports')->with('status', "Added successfully");
            } catch (\Exception $e) {
                return redirect('ports')->with('failed', "operation failed");
            }
        }
    }

    public function update(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            $data = $request->input();
            try {
                $port = new Port;
                $port->where("idport", '=', $request->idport)
                    ->update([
                        'idcountry' => $request->country,
                        'name' => $request->port,
                        'updated_at' => now()
                    ]);



                return redirect('ports')->with('status', "Updated successfully");
            } catch (\Exception $e) {
                return redirect('ports')->with('failed', "operation failed");
            }
        }
    }

    public function findCountryPorts(Request $request)
    {
        $country = new Country;
        $countries = $country->where('id', '=', $request->idcountry)->first();
        $port = new Port;
        $_ports = $port->where('idcountry', '=', $request->idcountry)->get();

        $response = collect([]);
        $response->push([
            'id' => "",
            'text' => "Select " . ($countries->name) . " Port",
        ]);
        foreach ($_ports as $_port) {
            $response->push([
                'id' => $_port->idport,
                'text' => $_port->name,
            ]);
        }

        return response()->json($response);
    }
}
