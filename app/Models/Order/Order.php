<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primarykey = 'idorders';
    public $timestamps = true;

    protected $fillable = [
		'idorders',
        'iduser',
        'idcustomer',
        'idstock',
        'status',
        'customerOrder',
        'date'
	];



    public function user()
    {
        return $this->belongsTo('App\MOdels\User', "iduser", "id");
    }

    public function customer()
    {
        return $this->belongsTo('App\MOdels\Customer', "idcustomer", "idcustomer");
    }

    public function stock()
    {
        return $this->belongsTo('App\MOdels\Stock\Stock', "idstock", "idstock");
    }

    public function booking()
    {
        return $this->hasOne('App\Models\Order\OrderPrice', "idorders", "idorders");
    }



    public function shipment()
    {
        return $this->belongsTo('App\MOdels\Order\OrderShipment', "idorders", "idorders");
    }

    public function shipmentSchedule()
    {
        return $this->belongsTo('App\MOdels\OrderShipSchedule', "idorders", "idorders");
    }



}
