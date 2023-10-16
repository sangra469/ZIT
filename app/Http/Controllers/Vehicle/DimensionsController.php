<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Vehicle\Models;
use App\Models\Vehicle\Maker;
use App\Models\Vehicle\Code;
use App\Models\Vehicle\Package;
use App\Models\Vehicle\Dimension;

use Illuminate\Support\Facades\Auth;


class DimensionsController extends Controller
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
            $title = "Vehicle's Model Dimensions";
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

            return view(Auth::user()->userrole->path . '/dimension', compact("title", "makers", "_models", "_codes", "_packages", "_dimensions"));
        }
    }
    public function indexUpdate(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Update Model Dimensions";
            $maker = new Maker;
            $model = new Models;
            $_code = new Code;
            $_package = new Package;
            $_dimension = new Dimension;
            $dimension = $_dimension->where('iddimension', '=', $request->id )->first();
            $makers = $maker->all();
            $_models = $model->where('idmaker', '=', $dimension->model->idmaker)->get();
            $_codes = $_code->where('idmodel', '=', $dimension->idmodel)->get();
            $_packages = $_package->where('idmodel', '=', $dimension->idmodel)->get();
            $_dimensions = $_dimension->all();

            return view(Auth::user()->userrole->path . '/dimension-update', compact("title", "dimension", "makers", "_models", "_codes", "_packages", "_dimensions"));
        }

    }

    public function addNew(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            $data = $request->input();
            try {
                $dim = new Dimension;
                $dim->idmodel = $data['model'];
                $dim->idpackage = $data['package'];
                $dim->idcode = $data['code'];
                $dim->mm1 = $data['mm1'];
                $dim->mm2 = $data['mm2'];
                $dim->mm3 = $data['mm3'];
                $dim->cm1 = $data['cm1'];
                $dim->cm2 = $data['cm2'];
                $dim->cm3 = $data['cm3'];
                $dim->m3 = ($data['cm1'] * $data['cm2'] * $data['cm3']) / 1000000;
                $dim->save();



                return redirect('dimensions')->with('status', "Added successfully");
            } catch (\Exception $e) {
                return redirect('dimensions')->with('failed', "operation failed");
            }
        }

    }

    public function update(Request $request){
        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            try {
                $dim = new Dimension;
                $dim->where("iddimension", '=', $request->iddimension)
                ->update([
                    'idpackage' => $request->package,
                    'idmodel' => $request->model,
                    'idcode' => $request->code,
                    'mm1' => $request->mm1,
                    'mm2' => $request->mm2,
                    'mm3' => $request->mm3,
                    'cm1' => $request->cm1,
                    'cm2' => $request->cm2,
                    'cm3' => $request->cm3,
                    'm3' => ($request->cm1 * $request->cm2 * $request->cm3) / 1000000 ,
                    'updated_at' => now()
                ]);

                return redirect('dimensions')->with('status', "Updated successfully");
            } catch (\Exception $e) {
                return redirect('dimensions')->with('failed', "operation failed");
            }

        }
    }


    public function findPkgDimension(Request $request)
    {

        $_dim = new Dimension;
        $dim = $_dim->where("idpackage", '=', $request->idpackage)->first();


        $response = collect([]);
        $response->push([
            'id' => "",
            'text' => "Select " . ($dim->model->maker->name) . " / " . ($dim->model->name) . " / " . ($dim->code->name) . " Package",
        ]);
        $response->push([
            'id' => $dim->iddimension,
            'text' => $dim->mm1 . "mm X " . $dim->mm2 . "mm X " . $dim->mm3 . "mm =" . $dim->m3 . " m3",
        ]);

        return response()->json($response);
    }
}
