<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Engine extends Model
{
    use HasFactory;

    protected $table = 'engine';
    protected $primarykey = 'idengine';
    public $timestamps = true;


    protected $fillable = [
		'name', 'idmaker'
	];


    // foregin keys in code
    // one to many relation will be like this
    public function maker()
    {
        return $this->hasOne('App\Models\Vehicle\Maker', "idmaker", "idmaker");
    }

    public function codes() {
        return $this->hasMany('App\Models\Stock\Code', 'idengine', 'idengine');
    }



}
