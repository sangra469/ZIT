<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderShipment extends Model
{
    use HasFactory;

    protected $table = 'ordershipment';
    protected $primarykey = 'idordershipment';
    public $timestamps = true;

    protected $fillable = [
		'idordershipment',
        'idorders',
        'idcountry',
        'idpod',
        'pol',
        'pod',
        'consignee',
        'importer',
        'cfs',
        'shipon'

	];

    public function order()
    {
        return $this->belongsTo('App\MOdels\Order\Order', "idorders", "idorders");
    }

    public function countryDelivery()
    {
        return $this->hasOne('App\Models\Country', "id", "idcountry");
    }

    public function portOfDelivery()
    {
        return $this->hasOne('App\Models\Port', "idport", "idpod");
    }


}
