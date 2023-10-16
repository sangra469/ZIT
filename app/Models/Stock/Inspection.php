<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    protected $table = 'inspection';
    protected $primarykey = 'idinspection';
    public $timestamps = true;


    protected $fillable = [
		'idstock', 'status', 'file', 'hide', 'expecteddate', 'inspectiondate', 'remarks'
	];


    // foregin keys in code  
    public function stock()
    {
        return $this->hasOne('App\Models\Stock\Stock', "idstock", "idstock");
    }



}
