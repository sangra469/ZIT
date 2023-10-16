<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remittance extends Model
{
    use HasFactory;
    protected $table = 'remittance';
    protected $primarykey = 'idremittance';
    public $timestamps = true;

    protected $fillable = [
		'idcustomer', 'iduser', 'idsoldprice', 'sender', 'amount', 'idcurrency', 'jpyrate'
	];




    // blongs to
    public function customer()
    {
        return $this->belongsTo('App\MOdels\Customer', "idcustomer", "idcustomer");
    }

    public function accountBook()
    {
        return $this->hasOne('App\Models\AccountBook', "idremittance", "idremittance");
    }

    public function usedRemittance()
    {
        return $this->hasMany('App\Models\Order\OrderPaymentsRemittance', "idremittance", "idremittance");
    }

    public function currency()
    {
        return $this->hasOne('App\Models\Currency', "idcurrency", "idcurrency");
    }

    public function currencies()
    {
        return $this->hasMany('App\Models\Currency', "idcurrency", "idcurrency");
    }


    public function price()
    {
        return $this->hasOne('App\Models\Order\OrderPrice', "idsoldprice", "idsoldprice");
    }



}
