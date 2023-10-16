<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $table = 'stockshipment';
    protected $primarykey = 'idstockshipment';
    public $timestamps = true;


    protected $fillable = [
		'idstock', 'transportdate', 'pol', 'pod', 'shipname', 'consignee', 'etd', 'eta'
	];


    // foregin keys in code
    public function stock()
    {
        return $this->hasOne('App\Models\Stock\Stock', "idstock", "idstock");
    }


}
