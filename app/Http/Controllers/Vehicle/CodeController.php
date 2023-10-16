<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Vehicle\Maker;
use App\Models\Vehicle\Models;
use App\Models\Vehicle\Code;
use App\Models\Vehicle\Drive;
use App\Models\Vehicle\Engine;
use App\Models\Vehicle\Steering;


use Illuminate\Support\Facades\Auth;

class CodeController extends Controller
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
            $title = "Model's Codes";
            $maker = new Maker;
            $makers = $maker->all();
            $model = new Models;
            $_models = $model->all();
            $_code = new Code;
            $_codes = $_code->all();
            $steering = new Steering;
            $steerings = $steering->all();
            $drive = new Drive;
            $drives = $drive->all();
            return view(Auth::user()->userrole->path . '/code', compact("title", "makers", "_models", "_codes", "steerings", "drives"));
        }
    }

    public function indexUpdate(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Update Model's Codes";
            $maker = new Maker;
            $model = new Models;
            $_code = new Code;
            $eng = new Engine;
            $code = $_code->where('idcode', '=', $request->id)->first();
            $makers = $maker->all();
            $_models = $model->where('idmaker', '=', $code->model->idmaker)->get();
            $_engs = $eng->where('idmaker', '=', $code->model->idmaker)->get();
            $_codes = $_code->all();
            $steering = new Steering;
            $steerings = $steering->all();
            $drive = new Drive;
            $drives = $drive->all();
            return view(Auth::user()->userrole->path . '/code-update', compact("title", "code", "makers", "_models", "_engs", "_codes", "steerings", "drives"));
        }
    }

    public function addNew(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            $data = $request->input();
            try {
                $code = new Code;
                $code->idmodel = $data['model'];
                $code->name = $data['code'];
                $code->url = strtolower(str_replace(" ", "-", $data['code']));
                $code->iddrive = $data['drive'];
                $code->idsteering = $data['steering'];
                $code->idengine = $data['engine'];
                $code->doors = $data['doors'];
                $code->seats = $data['seats'];
                $code->save();



                return redirect('codes')->with('status', "Added successfully");
            } catch (\Exception $e) {
                return redirect('codes')->with('failed', "operation failed");
            }
        }
    }

    public function update(Request $request){

        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            try {
                $code = new Code;
                $code->where("idcode", '=', $request->idcode)
                    ->update([
                        'idmodel' => $request->model,
                        'name' => $request->code,
                        'url' => strtolower(str_replace(" ", "-", $request->code)),
                        'iddrive' => $request->drive,
                        'idsteering' => $request->steering,
                        'idengine' => $request->engine,
                        'doors' => $request->doors,
                        'seats' => $request->seats,
                        'updated_at' => now()
                    ]);



                return redirect('codes')->with('status', "Update successfully");
            } catch (\Exception $e) {
                return $e;
                return redirect('codes')->with('failed', "operation failed");
            }
        }
    }

    public function findModel(Request $request)
    {
        $model = new Models;
        $_code = new Code;
        $_codes = $_code->where('idmodel', '=', $request->idmodel)->get();
        $_model = $model->where('idmodel', '=', $request->idmodel)->first();

        $response = collect([]);
        $response->push([
            'id' => "",
            'text' => "Select " . ($_model->maker->name) . " / " . ($_model->name) . " Code",
        ]);
        foreach ($_codes as $_code) {
            $response->push([
                'id' => $_code->idcode,
                'text' => $_code->name,
            ]);
        }

        return response()->json($response);
    }
}
