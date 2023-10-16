<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPayments extends Model
{
    use HasFactory;

    protected $table = 'orderpayments';
    protected $primarykey = 'orderpayments';
    public $timestamps = true;

    protected $fillable = [
		'iduser',
        'idorders',
        'description'

	];

    public function order()
    {
        return $this->belongsTo('App\MOdels\Order\Order', "idorders", "idorders");
    }

    public function user()
    {
        return $this->belongsTo('App\MOdels\User', "iduser", "id");
    }

    public function paymentRemittance()
    {
        return $this->hasOne('App\MOdels\Order\OrderPaymentsRemittance', "idpayment", "idorderpayments");
    }




}
