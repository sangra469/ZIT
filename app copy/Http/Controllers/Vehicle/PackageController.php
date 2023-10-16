<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Vehicle\Models;
use App\Models\Vehicle\Maker;
use App\Models\Vehicle\Code;
use App\Models\Vehicle\Package;

use Illuminate\Support\Facades\Auth;


class PackageController extends Controller
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
        $title = "Models Packages";
        $maker = new Maker;
        $makers = $maker->all();
        $model = new Models;
        $_models = $model->all();
        $_code = new Code;
        $_codes = $_code->all();
        $_package = new Package;
        $_packages = $_package->all();

        return view(Auth::user()->userrole->path . '/package', compact("title", "makers", "_models", "_codes", "_packages"));
    }

    public function indexUpdate(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Update Models Packages";
            $maker = new Maker;
            $makers = $maker->all();
            $model = new Models;
            $_models = $model->all();
            $_code = new Code;
            $_codes = $_code->all();
            $_package = new Package;
            $_packages = $_package->all();
            $package = $_package->where('idpackage', '=', $request->id)->first();

            return view(Auth::user()->userrole->path . '/package-update', compact("title", 'package', "makers", "_models", "_codes", "_packages"));
        }
    }

    public function addNew(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            $data = $request->input();
            try {
                $pkg = new Package;
                $pkg->idmodel = $data['model'];
                $pkg->idcode = $data['code'];
                $pkg->name = $data['package'];
                $pkg->url = strtolower(str_replace(" ", "-", $data['package']));
                $pkg->save();



                return redirect('package')->with('status', "Added successfully");
            } catch (\Exception $e) {
                return redirect('package')->with('failed', "operation failed");
            }
        }
    }

    public function update(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            try {
                $pkg = new Package;
                $pkg->where("idpackage", '=', $request->idpackage)
                    ->update([
                        'idmodel' => $request->model,
                        'idcode' => $request->code,
                        'name' => $request->package,
                        'url' => strtolower(str_replace(" ", "-", $request->package)),
                        'updated_at' => now()
                    ]);



                return redirect('package')->with('status', "Updated successfully");
            } catch (\Exception $e) {
                return redirect('package')->with('failed', "operation failed");
            }
        }
    }


    public function findCodeModel(Request $request)
    {
        $_code = new Code;
        $_pkg = new Package;
        $_pks = $_pkg->where("idcode", '=', $request->idcode)->get();

        $code = $_code->where('idcode', '=', $request->idcode)->first();

        $response = collect([]);
        $response->push([
            'id' => "",
            'text' => "Select " . ($code->model->maker->name) . " / " . ($code->model->name) . " / " . ($code->name) . " Package",
        ]);
        foreach ($_pks as $pkg) {
            $response->push([
                'id' => $pkg->idpackage,
                'text' => $pkg->name,
            ]);
        }

        return response()->json($response);
    }
}
