<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiclebody extends Model
{
    use HasFactory;
    protected $table = "vehiclebody";
    public $timestamps = true;
    protected $primarykey = 'idvehiclebody';

    protected $fillable = [
		'name', 'url'
	];



}
