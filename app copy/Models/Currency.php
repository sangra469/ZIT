<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    protected $table = 'currency';
    protected $primarykey = 'idcurrency';
    public $timestamps = true;

    protected $fillable = [
		'name', 'symbol', 'short', 'jpyrate'
	];




}
