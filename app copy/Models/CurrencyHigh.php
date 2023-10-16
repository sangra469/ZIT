<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyHigh extends Model
{
    use HasFactory;
    protected $table = 'currencyhigh';
    protected $primarykey = 'idcurrency';
    public $timestamps = true;

    protected $fillable = [
		'name', 'symbol', 'short', 'jpyrate'
	];


    public function soldPrice()
    {
        return $this->hasMany('App\Models\Order\OrderPrice', "idcurrency", "idcurrency");
    }


}
