<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountBook extends Model
{
    use HasFactory;

    protected $table = 'accountbook';
    protected $primarykey = 'idaccountbook';
    public $timestamps = true;

    protected $fillable = [
		'idremittance',
        'idpayments',
        'type',
        'amount',
        'description',
        'idcustomer'

	];


    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', "idcustomer", "idcustomer");
    }


}
