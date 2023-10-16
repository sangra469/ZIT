<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;





    // blongs to
    public function country()
    {
        return $this->belongsTo('App\Models\Stock\StockCountry', "idcountry", "id");
    }


    public function ports() {
        return $this->hasMany('App\Models\Port', 'idcountry', 'id');
    }



}
