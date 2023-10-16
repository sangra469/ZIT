<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    use HasFactory;
    protected $table = 'port';
    protected $primarykey = 'idport';
    public $timestamps = true;

    protected $fillable = [
		'name', 'idcountry'
	];




    // blongs to
    public function country()
    {
        return $this->hasOne('App\Models\Country', "id", "idcountry");
    }

}
