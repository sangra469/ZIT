<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;
    protected $table = 'shipment';
    protected $primarykey = 'idshipment';
    public $timestamps = true;

    protected $fillable = [
		'type', 'name', 'departure', 'pol'
	];




    // blongs to
    public function port()
    {
        return $this->hasOne('App\Models\Port', "idport", "pol");
    }



}
