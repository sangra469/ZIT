<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $table = 'package';
    protected $primarykey = 'idpackage';


    // foregin keys in package
    public function model()
    {
        return $this->hasOne('App\Models\Vehicle\Models', "idmodel", "idmodel");
    }
    public function code()
    {
        return $this->hasOne('App\Models\Vehicle\Code', "idcode", "idcode");
    }


    //
    public function dimension()
    {
        return $this->belongsTo('App\Models\Vehicle\Dimension', "idpackage", "idpackage");
    }
    public function dimensions()
    {
        return $this->hasMany('App\Models\Vehicle\Dimension', "idpackage", "idpackage");
    }




}
