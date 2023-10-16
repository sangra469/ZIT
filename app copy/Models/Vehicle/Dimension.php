<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dimension extends Model
{
    use HasFactory;
    protected $table = 'dimension';
    protected $primarykey = 'iddimension';
    public $timestamps = true;

    protected $fillable = [
		'idpackage', 'mm1', 'mm2', 'mm3', 'cm1', 'cm2', 'cm3', 'm3', 'idmodel', 'idcode'
	];

    // foregin keys in code
    public function model()
    {
        return $this->hasOne('App\Models\Vehicle\Models', "idmodel", "idmodel");
    }
    public function code()
    {
        return $this->hasOne('App\Models\Vehicle\Code', "idcode", "idcode");
    }
    public function package()
    {
        return $this->hasOne('App\Models\Vehicle\Package', "idpackage", "idpackage");
    }




}
