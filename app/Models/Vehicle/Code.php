<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;

    protected $table = 'code';
    protected $primarykey = 'idcode';
    public $timestamps = true;


    protected $fillable = [
		'idmodel', 'name', 'url', 'iddrive', 'idsteering', 'idengine', 'doors', 'seats'
	];


    // foregin keys in code
    public function model()
    {
        return $this->hasOne('App\Models\Vehicle\Models', "idmodel", "idmodel");
    }

    public function steering()
    {
        return $this->hasOne('App\Models\Vehicle\Steering', "idsteering", "idsteering");
    }
    public function drive()
    {
        return $this->hasOne('App\Models\Vehicle\Drive', "iddrive", "iddrive");
    }
    public function engine()
    {
        return $this->hasOne('App\Models\Vehicle\Engine', "idengine", "idengine");
    }


    //
    public function dimension()
    {
        return $this->belongsTo('App\Models\Vehicle\Dimension', "idcode", "idpackage");
    }

    public function package()
    {
        return $this->belongsTo('App\Models\Vehicle\Package', "idcode", "idcode");
    }


}
