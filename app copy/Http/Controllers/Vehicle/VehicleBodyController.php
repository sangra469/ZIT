<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle\Vehiclebody;
use ErlandMuchasaj\LaravelFileUploader\FileUploader;

use Illuminate\Support\Facades\Auth;

class VehicleBodyController extends Controller
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
        $title = "Vehicle Body Type";
        $body = new Vehiclebody;
        $bodies = $body->all();
        return view(Auth::user()->userrole->path . '/vehicleBody', compact("title", "bodies"));
    }

    public function indexUpdate(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Update Body Type";
            $_body = new Vehiclebody;
            $bodies = $_body->all();
            $body = $_body->where('idvehiclebody', '=', $request->id)->first();
            return view(Auth::user()->userrole->path . '/vehicleBody-update', compact("title", 'body', "bodies"));
        }
    }

    public function addNew(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $max_size = (int) ini_get('upload_max_filesize') * 1000;
            $extensions = implode(',', FileUploader::images());
            $request->validate([
                'body' => ['required', 'max:255'],
                'logo' => [
                    'required',
                    'file',
                    'image',
                    'mimes:' . $extensions,
                    'max:' . $max_size,
                ]
            ]);


            $fileName = strtolower(str_replace(" ", "-", $request->body)) . '.' . $request->logo->extension();

            $request->logo->move(public_path('logo'), $fileName);

            /*
            Write Code Here for
            Store $fileName name in DATABASE from HERE
            */


            // save data in database
            $data = $request->input();
            try {
                $body = new Vehiclebody;
                $body->name = $data['body'];
                $body->url = $fileName;
                $body->save();



                return redirect('vehicle-body')->with('status', "Added successfully");
            } catch (\Exception $e) {
                return redirect('vehicle-body')->with('failed', "operation failed");
            }
        }
    }

    public function update(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $max_size = (int) ini_get('upload_max_filesize') * 1000;
            $extensions = implode(',', FileUploader::images());
            $request->validate([
                'body' => ['max:255'],
                'logo' => [
                    'file',
                    'image',
                    'mimes:' . $extensions,
                    'max:' . $max_size,
                ]
            ]);


            if ($request->logo) {
                $fileName = strtolower(str_replace(" ", "-", $request->body)) . '.' . $request->logo->extension();
                $request->logo->move(public_path('logo'), $fileName);
            }

            /*
            Write Code Here for
            Store $fileName name in DATABASE from HERE
            */


            // save data in database
            try {
                $body = new Vehiclebody;
                $body->where("idvehiclebody", '=', $request->idvehiclebody)
                    ->update([
                        'name' => $request->body,
                        'updated_at' => now()
                    ]);

                if ($request->logo) {
                    $body->where("idvehiclebody", '=', $request->idvehiclebody)
                        ->update([
                            'url' => $fileName,
                            'updated_at' => now()
                        ]);
                }



                return redirect('vehicle-body')->with('status', "Added successfully");
            } catch (\Exception $e) {
                return redirect('vehicle-body')->with('failed', "operation failed");
            }
        }
    }
}
