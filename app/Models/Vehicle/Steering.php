<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Steering extends Model
{
    use HasFactory;

    protected $table = 'steering';
    protected $primarykey = 'idsteering';
    public $timestamps = true;


    protected $fillable = [
		'name'
	];


    // foregin keys in code
    public function codes() {
        return $this->hasMany('App\Models\Stock\Code', 'idsteering', 'idsteering');
    }


}
