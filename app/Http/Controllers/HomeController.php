<?php

namespace App\Http\Controllers;

use App\Models\CurrencyHigh;
use App\Models\Order\Order;
use App\Models\Order\OrderInvoice;
use App\Models\Order\OrderPrice;
use App\Models\Order\OrderShipment;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
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
        $title = "Dashboard";
        if (Auth::user()->userrole->iduserrole == 3) {
            $page = 'profile';
            $idcustomer = Auth::user()->customer->idcustomer;
            $order = new Order;
            $orderCurrency = new OrderPrice;
            $curHigh = new CurrencyHigh;
            $invoices = new OrderInvoice;
            $ordersContainer = $order->where('idcustomer', "=", Auth::user()->customer->idcustomer)
                ->whereHas("shipment", function ($query) {
                    return $query->where([
                        ['shipon', "=", 2],
                        ['revise', "=", 0]
                    ]);
                })->get();

            $orderCurrencies = $curHigh->whereHas("soldPrice", function ($query) use ($idcustomer) {
                if (!empty($idcustomer)) {
                    return $query->whereHas("order", function ($q2) use ($idcustomer) {
                        return $q2->where('idcustomer', "=", $idcustomer);
                    });
                }
            })
                ->get();
            $ordInvoices = $invoices->where("paid", '=', "No")
                ->whereHas("order", function ($query) use ($idcustomer) {
                    if (!empty($idcustomer)) {
                        return $query->where('idcustomer', "=", $idcustomer);
                    }
                })
                ->orderby('idorders')
                ->get();
            $odersByCur = collect([]);
            foreach ($orderCurrencies as $ordCur) {
                $cur = $ordCur->idcurrency;
                $short = $ordCur->short;
                $orders = $order->where('idcustomer', "=", Auth::user()->customer->idcustomer)
                    ->whereHas("booking", function ($query) use ($cur) {
                        if (!empty($cur)) {
                            return $query->where('idcurrency', "=", $cur);
                        }
                    })
                    ->get();
                $odersByCur->push([
                    "$short" => $orders,
                ]);
            }
            // return $ordersContainer;
            return view(Auth::user()->userrole->path . '/dashboard', compact("title", "page", "orderCurrencies", "odersByCur", "ordersContainer", "ordInvoices"));
        } else if (Auth::user()->userrole->iduserrole == 1) {
            $order = new Order;
            $orderCurrency = new OrderPrice;
            $curHigh = new CurrencyHigh;
            $invoices = new OrderInvoice;
            $user = new User;

            $users = $user
                ->whereHas("userrole", function ($query) {
                    return $query->where('iduserrole', "=", 3);
                })
                ->get();

            $orders = $order
                ->where([
                    ["status", "=", 1],
                    ["customerOrder", "=", 0],
                ])
                ->get();
            $bookings = $order
                ->where([
                    ["status", "=", 1],
                    ["customerOrder", "=", 1],
                ])
                ->get();
            $ordersRevise = $order
                ->whereHas("shipment", function ($query) {
                    return $query->where([
                        ['shipon', "=", 2],
                        ['revise', "=", 1]
                    ]);
                })->get();
            $pendingInvoices = $invoices
                ->where("paid", '=', "No")
                ->orderby('idorders')
                ->get();

            return view(Auth::user()->userrole->path . '/dashboard', compact("title", "orders", "bookings", "pendingInvoices", "ordersRevise", "users"));
        }

        return view(Auth::user()->userrole->path . '/dashboard', compact("title"));
    }

    public function customerPage(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 3) {
            $title = ucwords($request->page);
            $page = $request->page;
            $idcustomer = Auth::user()->customer->idcustomer;
            $order = new Order;
            $invoices = new OrderInvoice;
            $orderCurrency = new OrderPrice;
            $curHigh = new CurrencyHigh;
            $ordersContainer = $order->where('idcustomer', "=", Auth::user()->customer->idcustomer)
                ->whereHas("shipment", function ($query) {
                    return $query->where([
                        ['shipon', "=", 2],
                        ['revise', "=", 0]
                    ]);
                })->get();
            $orderCurrencies = $curHigh->whereHas("soldPrice", function ($query) use ($idcustomer) {
                if (!empty($idcustomer)) {
                    return $query->whereHas("order", function ($q2) use ($idcustomer) {
                        return $q2->where('idcustomer', "=", $idcustomer);
                    });
                }
            })->get();
            $ordInvoices = $invoices
                ->where("paid", '=', "No")
                ->whereHas("order", function ($query) use ($idcustomer) {
                    if (!empty($idcustomer)) {
                        return $query->where('idcustomer', "=", $idcustomer);
                    }
                })
                ->orderby('idorders')
                ->get();
            $odersByCur = collect([]);
            foreach ($orderCurrencies as $ordCur) {
                $cur = $ordCur->idcurrency;
                $short = $ordCur->short;
                $orders = $order->where('idcustomer', "=", Auth::user()->customer->idcustomer)
                    ->whereHas("booking", function ($query) use ($cur) {
                        if (!empty($cur)) {
                            return $query->where('idcurrency', "=", $cur);
                        }
                    })
                    ->get();
                $odersByCur->push([
                    "$short" => $orders,
                ]);
            }
            // return $ordersContainer;
            return view(Auth::user()->userrole->path . '/dashboard', compact("title", "page", "orderCurrencies", "odersByCur", "ordersContainer", "ordInvoices"));
        }
    }

    public function containerPrice(Request $request)
    {
        if (Auth::user()->userrole->iduserrole == 3) {

            $orderShip = new OrderShipment;



            try {

                foreach ($request->idorder as $id) {
                    $orderShip->where("idorders", "=", $id)->update(["revise" => 1]);
                }


                return redirect('dashboard')->with('status', "Requested Successfully");
            } catch (\Exception $e) {
                return redirect('dashboard')->with('failed', "operation failed");
            }
        }

        return view(Auth::user()->userrole->path . '/dashboard', compact("title"));
    }

    public function splitInvoice(Request $request)
    {

        if (Auth::user()->userrole->iduserrole == 3) {

            $invoices = new OrderInvoice;


            try {
                if ($request->percent1 + $request->percent2 == 100) {
                    $invoiceCnt = $invoices->where("idorders", "=", $request->orderId)->first();
                    $invoices->where([
                        ["idorderinvoice", ">", $invoiceCnt->idorderinvoice],
                        ["idorders", "=", $request->orderId],
                    ])->delete();

                    $invoice = $invoices->where("idorderinvoice", "=", $request->invoiceId)->update(["percent" => $request->percent1]);

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


                return redirect('dashboard/invoices')->with('status', "Requested Successfully");
            } catch (\Exception $e) {
                return redirect('dashboard/invoices')->with('failed', "operation failed");
            }
        }

        return view(Auth::user()->userrole->path . '/dashboard', compact("title"));
    }
}
