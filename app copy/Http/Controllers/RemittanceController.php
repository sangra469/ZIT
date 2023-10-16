<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccountBook;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Order\OrderPayments;
use App\Models\Order\OrderPaymentsRemittance;
use App\Models\Remittance;
use Illuminate\Http\Request;



use Illuminate\Support\Facades\Auth;


class RemittanceController extends Controller
{
    //////
    /**
     * Create a new controller instance.
     *
     * @return void`
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $title = "All Remittances";
        if (Auth::user()->userrole->iduserrole == 1) {
            $_remittance = new Remittance;
            $_currency = new Currency;
            $_customer = new Customer;
            $customers = $_customer->all();
            $currencies = $_currency->all();
            $remittances = $_remittance->all();

            return view(Auth::user()->userrole->path . '/remittances', compact("title", "remittances", "currencies", "customers"));
        }

        return redirect()->back()->with('fail', 'You do not have access to this page.');
    }


    public function addNew(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            $data = $request->input();
            try {
                $_currency = new Currency;
                $currency = $_currency->where('idcurrency', '=', $data['idcurrency'])->first();
                $remittance = new Remittance;
                $remittance->idcustomer = $data['customer'];
                $remittance->sender = $data['sender'];
                $remittance->amount = $data['amount'];
                $remittance->idcurrency = $data['idcurrency'];
                $remittance->jpyrate = $currency->jpyrate;

                $remittance->save();



                return redirect('remittance')->with('status', "Added successfully");
            } catch (\Exception $e) {
                return redirect('remittance')->with('failed', "operation failed");
            }
        }
    }

    public function indexUpdate(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Update Remittance";
            $_remittance = new Remittance;
            $_currency = new Currency;
            $_customer = new Customer;
            $customers = $_customer->all();
            $currencies = $_currency->all();
            $remittances = $_remittance->all();
            $remittance = $_remittance->where('idremittance', '=', $request->id)->first();

            return view(Auth::user()->userrole->path . '/remittance-update', compact("title", "remittance", "remittances", "currencies", "customers"));
        }
    }

    public function indexAcknowledge(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $title = "Acknowledge Remittance";
            $_remittance = new Remittance;
            $_currency = new Currency;
            $_customer = new Customer;
            $customers = $_customer->all();
            $currencies = $_currency->all();
            $remittances = $_remittance->all();
            $remittance = $_remittance->where('idremittance', '=', $request->id)->first();

            return view(Auth::user()->userrole->path . '/remittance-acknowledge', compact("title", "remittance", "remittances", "currencies", "customers"));
        }
    }



    public function update(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            try {
                $_remittance = new Remittance;
                $_remittance->where("idremittance", '=', $request->idremittance)
                    ->update([
                        'idcustomer' => $request->customer,
                        'sender' => $request->sender,
                        'amount' => $request->amount,
                        'idcurrency' => $request->idcurrency,
                        'updated_at' => now()
                    ]);



                return redirect('remittance')->with('status', "Updated successfully");
            } catch (\Exception $e) {
                return redirect('remittance')->with('failed', "operation failed");
            }
        }
    }

    public function acknowledge(Request $request)
    {
        // return Auth::user()->id;
        if (Auth::user()->userrole->iduserrole == 1) {
            // save data in database
            try {
                $accountBook = new AccountBook;
                $remittance = new Remittance;
                $_orderPayment = new OrderPaymentsRemittance;

                ///////////////////////////////////////////////////////////////////////////////////////
                $ordPay = $_orderPayment->where("idremittance", '=', $request->idremittance)->get();
                foreach ($ordPay as $pay) {
                    # code...
                    $this->reverseRemittanceAmount($pay->idpayment, $pay->accountBook->idcustomer, $pay->amount, $pay->payment->idorders);
                }


                ///////////////////////////////////////////////////////////////////////////////////////
                $remittance->where("idremittance", '=', $request->idremittance)
                    ->update([
                        'iduser' => Auth::user()->id,
                        'updated_at' => now()
                    ]);
                $accountBook->where("idremittance", '=', $request->idremittance)
                    ->update([
                        'idcustomer' => $request->customer,
                        'updated_at' => now()
                    ]);

                return redirect('remittance')->with('status', "Acknowledge successfully");
            } catch (\Exception $e) {
                return redirect('remittance')->with('failed', "operation failed");
            }
        }
    }
    private function reverseRemittanceAmount($idorderpayments, $idcustomer, $amount, $idorders)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            $accountBook = new AccountBook;
            $_payment = new OrderPayments;
            $accountBook->type = 1;
            $accountBook->amount = $amount;
            $accountBook->description = 'Reverse Payment Order ID ' . $idorders;
            $accountBook->idcustomer = $idcustomer;
            $accountBook->save();
            $_payment->where('idorderpayments', '=', $idorderpayments)->delete();
        }
    }


}
