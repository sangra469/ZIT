<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle\Transmission;

use Illuminate\Support\Facades\Auth;




class VehicleTransmissionController extends Controller
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
        $title = "Vehicle Transmission Type";
        $_trans = new Transmission;
        $trans = $_trans->all();
        return view(Auth::user()->userrole->path . '/transmission', compact("title", "trans"));
    }



    public function indexUpdate(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Vehicle Transmission Type";
            $_trans = new Transmission;
            $trans = $_trans->all();
            $transmission = $_trans->where('idtransmission', '=', $request->id)->first();

            return view(Auth::user()->userrole->path . '/transmission-update', compact("title", 'transmission', "trans"));
        }
    }

    public function addNew(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            $data = $request->input();
            try {
                $t = new Transmission;
                $t->name = $data['name'];
                $t->short = $data['short'];
                $t->save();



                return redirect('vehicle-transmission')->with('status', "Added successfully");
            } catch (\Exception $e) {
                return redirect('vehicle-transmission')->with('failed', "operation failed");
            }
        }
    }

    public function update(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            try {
                $t = new Transmission;
                $t->where("idtransmission", '=', $request->idtransmission)
                    ->update([
                        'name' => $request->name,
                        'short' => $request->short,
                        'updated_at' => now()
                    ]);



                return redirect('vehicle-transmission')->with('status', "Added successfully");
            } catch (\Exception $e) {
                return redirect('vehicle-transmission')->with('failed', "operation failed");
            }
        }
    }
}
