<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderInvoice extends Model
{
    use HasFactory;

    protected $table = 'orderinvoice';
    protected $primarykey = 'idorderinvoice';
    public $timestamps = true;

    protected $fillable = [
		'idorderinvoice',
        'idorders',
        'percent',
        'tos',
        'ref',
        'shipon',
        'corporate',
        'printdate',
        'paid'

	];

    public function order()
    {
        return $this->belongsTo('App\MOdels\Order\Order', "idorders", "idorders");
    }

}
