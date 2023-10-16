<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccountBook;
use App\Models\Country;
use App\Models\CurrencyHigh;
use App\Models\Freight;
use Illuminate\Http\Request;

use App\Models\Order\Order;
use App\Models\Order\OrderInvoice;
use App\Models\Order\OrderPayments;
use App\Models\Order\OrderPaymentsRemittance;
use App\Models\Order\OrderPrice;
use App\Models\Order\OrderShipment;
use App\Models\OrderShipSchedule;
use App\Models\Port;
use App\Models\Remittance;
use App\Models\Shipment;
use App\Models\StockExpense;

use App\Models\Stock\Stock;


use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    ////
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
        $title = "Stock List";
        if (Auth::user()->userrole->iduserrole == 1) {
            $stock = new Stock;
            $stocks = $stock->where("status", "=", 1)
                ->orWhere("original", "=", 0)
                ->orWhere("website", "=", 1)
                ->get();

            return view(Auth::user()->userrole->path . '/orderableStockList', compact("title", "stocks"));
        }

        return redirect()->back()->with('fail', 'You do not have access to this page.');
    }

    public function getOrders()
    {
        $title = "All Orders";
        if (Auth::user()->userrole->iduserrole == 1) {
            $order = new Order;
            $orders = $order->all();

            return view(Auth::user()->userrole->path . '/orders', compact("title", "orders"));
        }

        return redirect()->back()->with('fail', 'You do not have access to this page.');
    }
    public function getOrdersReal()
    {
        $title = "All Real Orders";
        if (Auth::user()->userrole->iduserrole == 1) {
            $order = new Order;
            $orders = $order
                ->where([
                    ["status", "=", 1],
                    ["customerOrder", "=", 0],
                ])
                ->get();

            return view(Auth::user()->userrole->path . '/orders', compact("title", "orders"));
        }

        return redirect()->back()->with('fail', 'You do not have access to this page.');
    }
    public function getOrdersBooking()
    {
        $title = "All Booking Orders";
        if (Auth::user()->userrole->iduserrole == 1) {
            $order = new Order;
            $orders = $order
                ->where([
                    ["status", "=", 1],
                    ["customerOrder", "=", 1],
                ])
                ->get();

            return view(Auth::user()->userrole->path . '/orders', compact("title", "orders"));
        }

        return redirect()->back()->with('fail', 'You do not have access to this page.');
    }
    public function getOrdersRevise()
    {
        $title = "Orders Revise Requests";
        if (Auth::user()->userrole->iduserrole == 1) {
            $order = new Order;
            $orders = $order
                ->whereHas("shipment", function ($query) {
                    return $query->where([
                        ['shipon', "=", 2],
                        ['revise', "=", 1]
                    ]);
                })->get();

            return view(Auth::user()->userrole->path . '/orders', compact("title", "orders"));
        }

        return redirect()->back()->with('fail', 'You do not have access to this page.');
    }
    public function getOrdersInvoices()
    {
        $title = "Orders Revise Requests";
        if (Auth::user()->userrole->iduserrole == 1) {
            $invoices = new OrderInvoice;
            $ordInvoices = $invoices
                ->where("paid", '=', "No")
                ->orderby('idorders')
                ->get();

            return view(Auth::user()->userrole->path . '/invoices', compact("title", "ordInvoices"));
        }

        return redirect()->back()->with('fail', 'You do not have access to this page.');
    }
    // order booking by customer
    public function OrderBooking(Request $request)
    {

        if (Auth::user()->userrole->iduserrole == 3) {

            try {
                $order = new Order;
                $shipment = new OrderShipment;
                $invoice = new OrderInvoice;
                $soldPrice = new OrderPrice;
                $stock = new Stock;
                $ports = new Port;
                $freight = new Freight;
                $expense = new StockExpense;
                $currency = new CurrencyHigh;
                $cur = $currency->where("idcurrency", "=", 3)->first();
                $details = $stock->where("idstock", "=", $request->stock)->first();
                $inspection = $expense->where('id', '=', 1)->first();
                $unit = $freight->where([
                    ["idport", "=", $request->port],
                    ['shiptype', "=", $request->shipon]
                ])
                    ->first();
                $insp = 0;
                $cnf = 0;
                if ($request->tos == 'cnf') {
                    if ($unit) {
                        if ($request->shipon == 1) {
                            $cnf = ($unit->unit * $cur->jpyrate)  * $details->dimension->m3;
                        } else {
                            $cnf = $unit->unit * $cur->jpyrate;
                        }
                    }
                }

                if ($request->inspection == 1) {
                    $insp = $inspection->amt;
                }

                $port = $ports->where("idport", "=", $request->port)->first();
                // save order
                $order->iduser = 1;
                $order->idcustomer = Auth::user()->customer->idcustomer;
                $order->idstock = $request->stock;
                $order->status = 1;
                $order->customerOrder = 1;
                $order->save();
                // save shipment
                $shipment->idorders = $order->id;
                $shipment->idcountry = $request->country;
                $shipment->idpod = $request->port;
                $shipment->pol = $details->shipment->pol;
                $shipment->pod = $port->name;
                $shipment->consignee = $request->consignee;
                $shipment->importer = $request->importer;
                $shipment->shipon = $request->shipon;
                $shipment->save();
                // save invoice
                $invoice->idorders = $order->id;
                $invoice->tos = $request->tos;
                $invoice->ref = $order->id;
                $invoice->shipon = $request->shipon;
                $invoice->corporate = "yes";
                $invoice->printdate = date("Y-m-d", strtotime("Y-m-d +3 days"));
                $invoice->save();
                // save sold price
                $soldPrice->idorders = $order->id;
                $soldPrice->freight = ($cnf / session()->get('rate'));
                $soldPrice->inspection = ($insp / session()->get('rate'));
                $soldPrice->price = ($details->fob / session()->get('rate'));
                $soldPrice->idcurrency = session()->get('typeCurrency');
                $soldPrice->jpyrate = session()->get('rate');
                $soldPrice->shipon = $request->shipon;
                $soldPrice->tos = $request->tos;
                $soldPrice->clearance = "no";
                $soldPrice->save();


                return redirect()->back()->with('success', 'Order Placed Check Your Profile');
            } catch (\Exception $e) {
                return redirect()->back()->with('fail', 'You cannot have more than 1 order.');
            }
        }

        return redirect()->back()->with('fail', 'You cannot book order');
    }
    // order booking by agent or admin
    public function addNewOrder(Request $request)
    {

        if (Auth::user()->userrole->iduserrole == 1) {

            try {
                $order = new Order;
                $shipment = new OrderShipment;
                $invoice = new OrderInvoice;
                $soldPrice = new OrderPrice;
                $stock = new Stock;
                $ports = new Port;
                $freight = new Freight;
                $expense = new StockExpense;
                $currency = new CurrencyHigh;
                $rate = $currency->where("idcurrency", "=", $request->currency)->first();
                $details = $stock->where("idstock", "=", $request->stock)->first();


                $port = $ports->where("idport", "=", $request->port)->first();
                // save order
                $order->iduser = Auth::user()->id;
                $order->idcustomer = $request->customer;
                $order->idstock = $request->stock;
                $order->status = 1;
                $order->customerOrder = 0;
                $order->save();
                // save shipment
                $shipment->idorders = $order->id;
                $shipment->idcountry = $request->country;
                $shipment->idpod = $request->port;
                $shipment->pol = $details->shipment->pol;
                $shipment->pod = $port->name;
                $shipment->consignee = $request->consignee;
                $shipment->importer = $request->importer;
                $shipment->shipon = $request->shipon;
                $shipment->save();
                // save invoice
                $invoice->idorders = $order->id;
                $invoice->tos = $request->tos;
                $invoice->ref = $order->id;
                $invoice->shipon = $request->shipon;
                $invoice->corporate = "yes";
                $invoice->printdate = date("Y-m-d", strtotime("Y-m-d +3 days"));
                $invoice->save();
                // save sold price
                $soldPrice->idorders = $order->id;
                $soldPrice->freight = $request->freight;
                $soldPrice->inspection = $request->insp;
                $soldPrice->price = $request->fob;
                $soldPrice->idcurrency = $request->currency;
                $soldPrice->jpyrate = $rate->jpyrate;
                $soldPrice->shipon = $request->shipon;
                $soldPrice->tos = $request->tos;
                $soldPrice->clearance = "no";
                $soldPrice->save();


                return redirect()->back()->with('success', 'Order Placed Goto Customer Profile');
            } catch (\Exception $e) {
                return redirect()->back()->with('fail', 'Customer cannot have more than 1 order.');
            }
        }



        return redirect()->back()->with('fail', 'You cannot book order');
    }

    // invoices
    public function splitInvoice(Request $request)
    {

        if (Auth::user()->userrole->iduserrole == 1) {

            $invoices = new OrderInvoice;


            try {
                if ($request->percent1 + $request->percent2 == 100) {
                    $invoiceCnt = $invoices->where("idorders", "=", $request->orderId)->first();
                    $invoices->where([
                        ["idorderinvoice", ">", $invoiceCnt->idorderinvoice],
                        ["idorders", "=", $request->orderId],
                    ])->delete();

                    $invoice = $invoices->where("idorderinvoice", "=", $request->invoiceId)
                        ->update([
                            "percent" => $request->percent1,
                            'updated_at' => now()
                        ]);

                    $oldInvoice = $invoices->where("idorderinvoice", "=", $request->invoiceId)->first();
                    $invoices->idorders = $oldInvoice->idorders;
                    $invoices->percent = $request->percent2;
                    $invoices->tos = $oldInvoice->tos;
                    $invoices->ref = $oldInvoice->ref;
                    $invoices->shipon = $oldInvoice->shipon;
                    $invoices->corporate = $oldInvoice->corporate;
                    $invoices->printdate = $oldInvoice->printdate;
                    $invoices->paid = $oldInvoice->paid;
                    $invoices->save();
                }


                return redirect()->back()->with('status', "Requested Successfully");
            } catch (\Exception $e) {
                return redirect()->back()->with('fail', 'Operation Failed');
            }
        }

        return redirect()->back()->with('fail', 'You do not have access to this page.');
    }

    // order update
    public function orderUpdate(Request $request)
    {
        $title = "Orders Update";
        if (Auth::user()->userrole->iduserrole == 1) {
            $order = new Order;
            $country = new Country;
            $currency = new CurrencyHigh;
            $expense = new StockExpense;
            $stock = new Stock;
            $stockExpense = $expense->where('id', '=', 1)->first();
            $inspection = $stockExpense->amt;
            $currencies = $currency->get();
            $countries = $country->get();
            $details = $order
                ->where("idorders", "=", $request->id)
                ->first();

            $idstock = $details->idstock;
            if ($request->stock) {
                $idstock = $request->stock;
            }

            $stocks = $stock
                ->where([
                    ['idmodel', '=', $details->stock->idmodel],
                    ['status', '=', 1],
                    ['original', '=', 1],
                    ['webstatus', '=', 0],
                ])
                ->WhereDoesntHave("order")
                ->get();
            $detailsstock = $stock
                ->where([
                    ['idstock', '=', $idstock],
                    ['idmodel', '=', $details->stock->idmodel]
                ])
                ->first();
            if ($detailsstock) {
                $detailsStock = $detailsstock;
            } else {
                $detailsStock = $stock
                    ->where([
                        ['idstock', '=', $details->idstock],
                        ['idmodel', '=', $details->stock->idmodel]
                    ])
                    ->first();
            }


            return view(Auth::user()->userrole->path . '/order-update', compact("title", "details", "detailsStock", "countries", "currencies", "inspection", "stocks"));
        }

        return redirect()->back()->with('fail', 'You do not have access to this page.');
    }
    public function orderUpdateNow(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {

            try {
                $order = new Order;
                $shipment = new OrderShipment;
                $invoice = new OrderInvoice;
                $soldPrice = new OrderPrice;
                $stock = new Stock;
                $ports = new Port;
                $freight = new Freight;
                $expense = new StockExpense;
                $currency = new CurrencyHigh;
                $rate = $currency->where("idcurrency", "=", $request->currency)->first();
                $details = $stock->where("idstock", "=", $request->stock)->first();


                $port = $ports->where("idport", "=", $request->port)->first();
                // save order
                $order->where("idorders", '=', $request->orderId)
                    ->update([
                        'idstock' => $request->stock,
                        'customerOrder' => 0,
                        'updated_at' => now()
                    ]);

                // save shipment
                $shipment->where("idorders", '=', $request->orderId)
                    ->update([
                        'idcountry' => $request->country,
                        'idpod' => $request->port,
                        'pod' => $port->name,
                        'consignee' => $request->consignee,
                        'importer' => $request->importer,
                        'cfs' => $request->cfs,
                        'shipon' => $request->shipon,
                        'revise' => 2,
                        'updated_at' => now()
                    ]);

                // save invoice
                $invoice->where("idorders", '=', $request->orderId)
                    ->update([
                        'tos' => $request->tos,
                        'shipon' => $request->shipon,
                        'updated_at' => now()
                    ]);
                // save sold price
                $soldPrice->where("idorders", '=', $request->orderId)
                    ->update([
                        'price' => $request->fob,
                        'freight' => $request->freight,
                        'inspection' => $request->insp,
                        'idcurrency' => $request->currency,
                        'jpyrate' => $rate->jpyrate,
                        'shipon' => $request->shipon,
                        'revise' => 1,
                        'tos' => $request->tos,
                        'updated_at' => now()
                    ]);


                return redirect()->back()->with('success', 'Order Updated');
            } catch (\Exception $e) {
                return redirect()->back()->with('fail', 'Customer cannot have more than 1 order.');
            }
        }



        return redirect()->back()->with('fail', 'You cannot book order');
    }
    // order Shipment
    public function orderShipment(Request $request)
    {
        $title = "Order Shipment";
        if (Auth::user()->userrole->iduserrole == 1) {
            $order = new Order;
            $country = new Country;
            $currency = new CurrencyHigh;
            $expense = new StockExpense;
            $stock = new Stock;
            $_ships = new Shipment;
            $stockExpense = $expense->where('id', '=', 1)->first();
            $inspection = $stockExpense->amt;
            $currencies = $currency->get();
            $countries = $country->get();
            $shipments = $_ships->where('departure', '>=', now())->get();
            $details = $order
                ->where("idorders", "=", $request->id)
                ->first();

            $idstock = $details->idstock;
            if ($request->stock) {
                $idstock = $request->stock;
            }

            $stocks = $stock
                ->where([
                    ['idmodel', '=', $details->stock->idmodel],
                    ['status', '=', 1],
                    ['original', '=', 1],
                    ['webstatus', '=', 0],
                ])
                ->WhereDoesntHave("order")
                ->get();
            $detailsstock = $stock
                ->where([
                    ['idstock', '=', $idstock],
                    ['idmodel', '=', $details->stock->idmodel]
                ])
                ->first();
            if ($detailsstock) {
                $detailsStock = $detailsstock;
            } else {
                $detailsStock = $stock
                    ->where([
                        ['idstock', '=', $details->idstock],
                        ['idmodel', '=', $details->stock->idmodel]
                    ])
                    ->first();
            }


            return view(Auth::user()->userrole->path . '/order-shipment', compact("title", "details", "detailsStock", "shipments", "countries", "currencies", "inspection", "stocks"));
        }

        return redirect()->back()->with('fail', 'You do not have access to this page.');
    }
    public function updateOrderShipment(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            try {
                //code...


                $shipment = new OrderShipSchedule;
                $schedule = $shipment->where('idorders', '=', $request->idorders);
                if ($schedule->exists()) {
                    $schedule->delete();
                }

                $shipment->idorders = $request->idorders;
                $shipment->idshipment = $request->idshipment;
                $shipment->save();



                return redirect()->back()->with('success', 'Shipping Schedule Updated');
            } catch (\Exception $e) {
                return redirect()->back()->with('fail', 'Operation Failed');
            }
        }
    }
    // Order Payments
    public function orderPayments(Request $request)
    {
        $title = "Order Payments";
        if (Auth::user()->userrole->iduserrole == 1) {
            $order = new Order;
            $country = new Country;
            $currency = new CurrencyHigh;
            $expense = new StockExpense;
            $stock = new Stock;
            $_ships = new Shipment;
            $_payment = new OrderPayments;
            $_remittance = new Remittance;

            $payments = $_payment->where("idorders", "=", $request->id)->get();

            $stockExpense = $expense->where('id', '=', 1)->first();
            $inspection = $stockExpense->amt;
            $currencies = $currency->get();
            $countries = $country->get();
            $shipments = $_ships->where('departure', '>=', now())->get();
            $details = $order
                ->where("idorders", "=", $request->id)
                ->first();
            $idcustomer = $details->idcustomer;
            $remittances = $_remittance
                ->where([
                    ['idcustomer', '=', $details->idcustomer],
                    ['idcurrency', '=', $details->booking->idcurrency]
                ])
                ->whereHas("accountBook", function ($query) use ($idcustomer) {
                    return $query->where('idcustomer', '=', $idcustomer);
                })
                ->get();
            $idstock = $details->idstock;
            if ($request->stock) {
                $idstock = $request->stock;
            }

            $stocks = $stock
                ->where([
                    ['idmodel', '=', $details->stock->idmodel],
                    ['status', '=', 1],
                    ['original', '=', 1],
                    ['webstatus', '=', 0],
                ])
                ->WhereDoesntHave("order")
                ->get();
            $detailsstock = $stock
                ->where([
                    ['idstock', '=', $idstock],
                    ['idmodel', '=', $details->stock->idmodel]
                ])
                ->first();
            if ($detailsstock) {
                $detailsStock = $detailsstock;
            } else {
                $detailsStock = $stock
                    ->where([
                        ['idstock', '=', $details->idstock],
                        ['idmodel', '=', $details->stock->idmodel]
                    ])
                    ->first();
            }


            return view(Auth::user()->userrole->path . '/order-payment', compact("title", "details", "detailsStock", "shipments", "payments", "remittances", "countries", "currencies", "inspection", "stocks"));
        }

        return redirect()->back()->with('fail', 'You do not have access to this page.');
    }
    public function updateOrderPayments(Request $request)
    {
        // return $request;
        if (Auth::user()->userrole->iduserrole == 1) {
            try {
                //code...
                $order = new Order;
                $_payment = new OrderPayments;
                $_orderPayment = new OrderPaymentsRemittance;
                $_remittance = new Remittance;
                $_order = new Order;
                $order = $_order->where('idorders', '=', $request->idorders)->first();
                $remittance = $_remittance->where('idremittance', '=', $request->idremittance)->first();

                $details = $order
                    ->where("idorders", "=", $request->idorders)
                    ->first();
                $payments = $_payment->where("idorders", "=", $request->idorders)->get();
                $sum = 0;
                foreach ($payments as $pay) {
                    # code...
                    $sum += $pay->paymentRemittance->amount;
                }
                $remaining = ($details->booking->price + $details->booking->inspection + $details->booking->freight) - $sum;

                if (($remittance->amount >= ($request->amount + $remittance->usedRemittance->sum('amount'))) && $remaining >= $request->amount) {
                    $_payment->iduser = Auth::user()->id;
                    $_payment->idorders = $request->idorders;
                    $_payment->description = $request->description;
                    $_payment->save();

                    $_orderPayment->idremittance = $request->idremittance;
                    $_orderPayment->idpayment = $_payment->id;
                    $_orderPayment->amount = $request->amount;
                    $_orderPayment->jpyamount = ($request->amount * $remittance->jpyrate);
                    $_orderPayment->idcustomer = $order->idcustomer;
                    $_orderPayment->save();

                    return redirect()->back()->with('success', 'Payment Successful');
                }



                return redirect()->back()->with('fail', 'Payment Exceed.');
            } catch (\Exception $e) {
                return redirect()->back()->with('fail', 'Operation Fail');
            }
        }
    }

    public function deleteOrderPayments(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 1) {
            try {
                //code...
                $order = new Order;
                $_payment = new OrderPayments;
                $_orderPayment = new OrderPaymentsRemittance;
                $_remittance = new Remittance;
                $_order = new Order;
                $accountBook = new AccountBook;
                $ordPay = $_orderPayment->where('idpayment', '=', $request->idorderpayments)->first();
                $details = $order
                    ->where("idorders", "=", $ordPay->payment->idorders)
                    ->first();
                $payments = $_payment->where("idorders", "=", $ordPay->payment->idorders)->get();
                $sum = 0;
                foreach ($payments as $pay) {
                    # code...
                    $sum += $pay->paymentRemittance->amount;
                }
                $remaining = ($details->booking->price + $details->booking->inspection + $details->booking->freight) - $sum;
                if ($remaining > 0) {

                    $accountBook->type = 1;
                    $accountBook->amount = $ordPay->amount;
                    $accountBook->description = 'Reverse Payment Order ID ' . $ordPay->payment->idorders;
                    $accountBook->idcustomer = $ordPay->accountBook->idcustomer;
                    $accountBook->save();

                    $_payment->where('idorderpayments', '=', $request->idorderpayments)->delete();


                    return redirect()->back()->with('success', 'Payment Deleted');
                }


                return redirect()->back()->with('fail', 'Payment Completed');
            } catch (\Exception $e) {
                return redirect()->back()->with('fail', 'Operation Fail');
            }
        }
    }
}
