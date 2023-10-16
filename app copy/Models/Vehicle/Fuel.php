<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fuel extends Model
{
    use HasFactory;

    protected $table = "fuel";
    public $timestamps = true;
    protected $primarykey = 'idfuel';

    protected $fillable = [
		'name', 'short'
	];



}
