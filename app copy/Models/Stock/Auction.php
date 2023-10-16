<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $table = 'auction';
    protected $primarykey = 'idauction';
    public $timestamps = true;


    protected $fillable = [
		'idstock', 'place', 'lot', 'grade', 'sheet', 'recycle', 'reauction'
	];


    // foregin keys in code
    public function stock()
    {
        return $this->hasOne('App\Models\Stock\Stock', "idstock", "idstock");
    }


}
