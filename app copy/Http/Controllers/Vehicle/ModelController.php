<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Vehicle\Models;
use App\Models\Vehicle\Maker;

use Illuminate\Support\Facades\Auth;


class ModelController extends Controller
{
    //
    //

    /**
     * Create a new controller instance.
     *
     * @return void`
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $title = "Models";
        $maker = new Maker;
        $makers = $maker->all();
        $model = new Models;
        $_models = $model->all();
        return view(Auth::user()->userrole->path . '/models', compact("title", "makers", "_models"));
    }

    public function indexUpdate(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Models";
            $maker = new Maker;
            $_model = new Models;
            $makers = $maker->all();
            $_models = $_model->all();
            $model = $_model->where("idmodel", "=", $request->id)->first();
            return view(Auth::user()->userrole->path . '/model-update', compact("title", "model", "makers", "_models"));
        }
    }

    public function addNew(Request $request)
    {

        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            $data = $request->input();
            try {
                $model = new Models;
                $model->idmaker = $data['maker'];
                $model->name = $data['model'];
                $model->url = strtolower(str_replace(" ", "-", $data['model']));
                $model->save();



                return redirect('model')->with('status', "Added successfully");
            } catch (\Exception $e) {
                return redirect('model')->with('failed', "operation failed");
            }
        }
    }
    public function update(Request $request)
    {

        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            try {
                $model = new Models;
                $model->where("idmodel", '=', $request->idmodel)
                    ->update([
                        'idmaker' => $request->maker,
                        'name' => $request->model,
                        'url' => strtolower(str_replace(" ", "-", $request->model)),
                        'updated_at' => now()
                    ]);



                return redirect('model')->with('status', "Updated successfully");
            } catch (\Exception $e) {
                return redirect('model')->with('failed', "operation failed");
            }
        }
    }

    public function addModel($id, $name)
    {
        $model = new Models;
        $model->idmaker = $id;
        $model->name = $name;
        $model->url = strtolower(str_replace(" ", "-", $name));
        $model->save();
    }

    public function findMaker(Request $request)
    {
        $_maker = new Maker;
        $maker = $_maker->where('idmaker', '=', $request->idmaker)->first();
        $model = new Models;
        $_models = $model->where('idmaker', '=', $request->idmaker)->get();

        $response = collect([]);
        $response->push([
            'id' => "",
            'text' => "Select " . ($maker->name) . " Model",
        ]);
        foreach ($_models as $_model) {
            $response->push([
                'id' => $_model->idmodel,
                'text' => $_model->name,
            ]);
        }

        return response()->json($response);
    }
}
