<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPaymentsRemittance extends Model
{
    use HasFactory;

    protected $table = 'orderpaymentremittance';
    protected $primarykey = 'idorderpaymentremittance';
    public $timestamps = true;

    protected $fillable = [
		'idremittance',
        'idpayment',
        'amount',
        'jpyamount',
        'idcustomer'

	];

    public function remittance()
    {
        return $this->hasOne('App\MOdels\Remittance', "idremittance", "idremittance");
    }

    public function payment()
    {
        return $this->hasOne('App\MOdels\Order\OrderPayments', "idorderpayments", "idpayment");
    }
    public function accountBook()
    {
        return $this->hasOne('App\MOdels\AccountBook', "idpayments", "idorderpaymentremittance");
    }

    public function customer()
    {
        return $this->hasOne('App\MOdels\Customer', "idcustomer", "idcustomer");
    }


}
