<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drive extends Model
{
    use HasFactory;

    protected $table = 'drive';
    protected $primarykey = 'iddrive';
    public $timestamps = true;


    protected $fillable = [
		'name', 'url'
	];


    // foregin keys in code
    // one to many relation will be like this
    public function codes() {
        return $this->hasMany('App\Models\Stock\Code', 'iddrive', 'iddrive');
    }




}
