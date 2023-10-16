<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use App\Models\Vehicle\Maker;
use Illuminate\Support\Facades\Storage;

use ErlandMuchasaj\LaravelFileUploader\FileUploader;




use Illuminate\Support\Facades\Auth;


class MakeController extends Controller
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
        $title = "Brands/Makers";
        $maker = new Maker;
        $makers = $maker->all();
        return view(Auth::user()->userrole->path . '/makers', compact("title", "makers"));
    }

    public function indexUpdate(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Update Brand/Maker";
            $maker = new Maker;
            $makers = $maker->all();
            $make = $maker->where("idmaker", '=', $request->id)->first();
            return view(Auth::user()->userrole->path . '/maker-update', compact("title", "make", "makers"));
        }
    }

    public function addNew(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $max_size = (int) ini_get('upload_max_filesize') * 1000;
            $extensions = implode(',', FileUploader::images());
            $request->validate([
                'maker' => ['required', 'max:255'],
                'logo' => [
                    'required',
                    'file',
                    'image',
                    'mimes:' . $extensions,
                    'max:' . $max_size,
                ]
            ]);


            $fileName = strtolower(str_replace(" ", "-", $request->maker)) . '.' . $request->logo->extension();

            $request->logo->move(public_path('logo'), $fileName);

            /*
            Write Code Here for
            Store $fileName name in DATABASE from HERE
            */


            // save data in database
            $data = $request->input();
            try {
                $maker = new Maker;
                $maker->name = $data['maker'];
                $maker->url = $fileName;
                $maker->save();



                return redirect('maker')->with('status', "Added successfully");
            } catch (\Exception $e) {
                return redirect('maker')->with('failed', "operation failed");
            }
        }
    }

    public function update(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $max_size = (int) ini_get('upload_max_filesize') * 1000;
            $extensions = implode(',', FileUploader::images());
            $request->validate([
                'maker' => ['max:255'],
                'logo' => [
                    'file',
                    'image',
                    'mimes:' . $extensions,
                    'max:' . $max_size,
                ]
            ]);

            if ($request->logo) {
                $fileName = strtolower(str_replace(" ", "-", $request->maker)) . '.' . $request->logo->extension();
                $request->logo->move(public_path('logo'), $fileName);
            }


            /*
            Write Code Here for
            Store $fileName name in DATABASE from HERE
            */


            // save data in database
            $data = $request->input();
            try {
                $maker = new Maker;
                $maker->where("idmaker", '=', $request->idmaker)
                    ->update([
                        'name' => $request->maker,
                        'updated_at' => now()
                    ]);

                if ($request->logo) {
                    $maker->where("idmaker", '=', $request->idmaker)
                        ->update([
                            'url' => $fileName,
                            'updated_at' => now()
                        ]);
                }

                return redirect('maker')->with('status', "Updated successfully");
            } catch (\Exception $e) {
                return redirect('maker')->with('failed', "operation failed");
            }
        }
    }
    //update end

    //
}
