<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockCountry extends Model
{
    use HasFactory;

    protected $table = 'countrystock';
    protected $primarykey = 'idcountrystock';
    public $timestamps = true;


    protected $fillable = [
		'idstock', 'idcountry', 'name'
	];


    // foregin keys in code
    public function stock()
    {
        return $this->hasOne('App\Models\Stock\Stock', "idstock", "idstock");
    }
    public function country()
    {
        // primary key from second table
        // forign key name from current table
        return $this->hasOne('App\Models\Country', "id", "idcountry");
    }




}
