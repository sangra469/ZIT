<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Models extends Model
{
    use HasFactory;
    protected $table = 'model';
    protected $primarykey = 'idmodel';
    public $timestamps = true;

    protected $fillable = [
		'idmaker', 'name', 'url'
	];


    // foregin keys in models
    public function maker()
    {
        return $this->hasOne('App\Models\Vehicle\Maker', "idmaker", "idmaker");
    }

    public function stocks() {
        return $this->hasMany('App\Models\Stock\Stock', 'idmodel', 'idmodel');
    }

    //

    public function code()
    {
        return $this->belongsTo('App\Models\Vehicle\Code', "idmodel", "idmodel");
    }
    public function codes()
    {
        return $this->hasMany('App\Models\Vehicle\Code', "idmodel", "idmodel");
    }
    public function package()
    {
        return $this->belongsTo('App\Models\Vehicle\Package', "idmodel", "idmodel");
    }
    public function packages()
    {
        return $this->hasMany('App\Models\Vehicle\Package', "idmodel", "idmodel");
    }
    public function dimension()
    {
        return $this->belongsTo('App\Models\Vehicle\Dimension', "idmodel", "idmodel");
    }

}
