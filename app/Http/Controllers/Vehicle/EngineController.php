<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle\Engine;
use App\Models\Vehicle\Maker;



use Illuminate\Support\Facades\Auth;

class EngineController extends Controller
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
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Engine Code";
            $maker = new Maker;
            $makers = $maker->all();
            $eng = new Engine;
            $engines = $eng->all();
            return view(Auth::user()->userrole->path . '/engine', compact("title", "makers", "engines"));
        }

    }

    public function indexUpdate(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Update Engine Code";
            $maker = new Maker;
            $makers = $maker->all();
            $eng = new Engine;
            $engine = $eng->where('idengine', '=', $request->id)->first();
            $engines = $eng->all();
            return view(Auth::user()->userrole->path . '/engine-update', compact("title", "engine", "makers", "engines"));
        }

    }

    public function addNew(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            $data = $request->input();
            try {
                $engine = new Engine;
                $engine->idmaker = $data['maker'];
                $engine->name = $data['engine'];
                $engine->save();



                return redirect('engine')->with('status', "Added successfully");

            } catch (\Exception $e) {
                return redirect('engine')->with('failed', "operation failed");
            }
        }


    }

    public function update(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            try {
                $engine = new Engine;
                $engine->where("idengine", '=', $request->idengine)
                ->update([
                    'name' => $request->engine,
                    'idmaker' => $request->maker,
                    'updated_at' => now()
                ]);



                return redirect('engine')->with('status', "Update successfully");

            } catch (\Exception $e) {
                return redirect('engine')->with('failed', "operation failed");
            }
        }


    }


    public function findMaker(Request $request){
        $_maker = new Maker;
        $maker = $_maker->where('idmaker', '=', $request->idmaker)->first();
        $eng = new Engine;
        $_engs = $eng->where('idmaker', '=', $request->idmaker)->get();

        $response = collect([]);
        $response->push([
            'id' => "",
            'text' => "Select ". ($maker->name)."'s Engine",
        ]);
        foreach ($_engs as $_eng) {
            $response->push([
                'id' => $_eng->idengine,
                'text' => $_eng->name,
            ]);
        }

        return response()->json($response);
    }



}
