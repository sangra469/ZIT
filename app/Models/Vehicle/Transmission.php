<?php

namespace App\Models\Vehicle;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transmission extends Model
{
    use HasFactory;
    protected $table = "transmission";
    public $timestamps = true;
    protected $primarykey = 'idtransmission';

    protected $fillable = [
		'name', 'short'
	];



}
