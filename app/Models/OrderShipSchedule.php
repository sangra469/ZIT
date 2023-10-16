<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderShipSchedule extends Model
{
    use HasFactory;
    protected $table = 'ordershipschedule';
    protected $primarykey = 'idordershipschedule';
    public $timestamps = true;

    protected $fillable = [
		'idorders', 'idshipment'
	];




    // blongs to
    public function order()
    {
        return $this->hasOne('App\Models\Order\Order', "idorders", "idorders");
    }

    public function shipment()
    {
        return $this->hasOne('App\Models\Shipment', "idshipment", "idshipment");
    }

}
