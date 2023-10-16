<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    use HasFactory;

    protected $table = 'stockdocuments';
    protected $primarykey = 'idstockdocuments';
    public $timestamps = true;


    protected $fillable = [
		'idstock', 'export', 'exporteng', 'preserverecord', 'preserverecordeng', 'bol', 'sur', 'finalinvoice', 'carrycuminvoice'
	];


    // foregin keys in code
    public function stock()
    {
        return $this->hasOne('App\Models\Stock\Stock', "idstock", "idstock");
    }


}
