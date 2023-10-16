<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Freight extends Model
{

    use HasFactory;
    protected $table = 'freight';
    protected $primarykey = 'id';
    public $timestamps = true;

    protected $fillable = [
		'idport', 'shiptype', 'unit'
	];




    // blongs to
    public function port()
    {
        return $this->hasOne('App\Models\Port', "idport", "idport");
    }


}
