<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customer';
    protected $primarykey = 'idcustomer';
    public $timestamps = true;

    protected $fillable = [
		'iduser', 'fname', 'lname', 'cname', 'phone', 'altemail', 'countryid', 'cityid', 'port'
	];




    // blongs to
    public function country()
    {
        return $this->hasOne('App\Models\Country', "id", "countryid");
    }
    public function city()
    {
        return $this->hasOne('App\Models\City', "id", "cityid");
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', "id", "iduser");
    }



}
