<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


use Illuminate\Support\Facades\Auth;


class CustomerController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $title = "Add New Customer";
        if (Auth::user()->userrole->iduserrole == 1) {
            $country = new Country;
            $countries = $country->all();

            return view(Auth::user()->userrole->path . '/addCustomer', compact("title", "countries"));
        }

        return redirect()->back()->with('fail', 'You do not have access to this page.');
    }

    public function getCustomer(){
        $title = "All Customer";
        if (Auth::user()->userrole->iduserrole == 1) {
            $customer = new Customer;
            $customers = $customer->all();

            return view(Auth::user()->userrole->path . '/listCustomer', compact("title", "customers"));
        }

        return redirect()->back()->with('fail', 'You do not have access to this page.');
    }


    protected function addNew(Request $request)
    {

        $data = $request->input();
        try {
            $customer = new Customer;
            $user = User::create([
                'iduserrole' => 3,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $customer->iduser = $user->id;
            $customer->fname = $data['name'];
            $customer->lname = $data['lname'];
            $customer->cname = $data['cname'];
            $customer->phone = $data['phone'];
            $customer->phone1 = $data['phone1'];
            $customer->countryid = $data['country'];
            $customer->cityid = 63260;
            //$customer->port = $data['port'];

            $customer->save();

            return redirect()->back()->with('success', 'Customer Added Successfuly.');
        } catch (\Exception $e) {
            return redirect()->back()->with('fail', 'You do not have access to this page.');
        }

        return redirect()->back()->with('fail', 'You do not have access to this page.');
    }


}
