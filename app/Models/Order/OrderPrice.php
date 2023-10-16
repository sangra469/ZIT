<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPrice extends Model
{
    use HasFactory;

    protected $table = 'soldprice';
    protected $primarykey = 'idsoldprice';
    public $timestamps = true;

    protected $fillable = [
		'idsoldprice',
        'idorders',
        'freight',
        'inspection',
        'price',
        'idcurrency',
        'jpyrate',
        'tos',
        'clearance'

	];

    public function order()
    {
        return $this->belongsTo('App\MOdels\Order\Order', "idorders", "idorders");
    }

    public function currency()
    {
        return $this->hasOne('App\Models\CurrencyHigh', "idcurrency", "idcurrency");
    }

    public function currencies()
    {
        return $this->hasMany('App\Models\CurrencyHigh', "idcurrency", "idcurrency");
    }


}
