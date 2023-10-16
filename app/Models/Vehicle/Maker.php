<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maker extends Model
{
    use HasFactory;
    protected $table = 'maker';
    protected $primarykey = 'idmaker';
    public $timestamps = true;


    protected $fillable = [
		'name', 'url'
	];


    // one to many relation will be like this
    public function stock() {
        return $this->hasMany('App\Models\Stock\Stock', 'idmaker', 'idmaker');
    }
    public function engine() {
        return $this->hasMany('App\Models\Stock\Engine', 'idmaker', 'idmaker');
    }



    public function model()
    {
        return $this->belongsTo('App\Models\Vehicle\Models', "idmaker", "idmaker");
    }
    public function models() {
        return $this->hasMany('App\Models\Vehicle\Models', "idmaker", "idmaker");
    }






}
