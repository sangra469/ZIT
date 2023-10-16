<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockExpense extends Model
{
    use HasFactory;
    protected $table = 'stock_expense';
    protected $primarykey = 'idport';
    public $timestamps = true;

    protected $fillable = [
		'name', 'amt'
	];




}
