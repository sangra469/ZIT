<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stock';
    protected $primarykey = 'idstock';

    protected $fillable = [
		'idmaker',
        'idmodel',
        'idcode',
        'idpackage',
        'idvehiclebody',
        'idtransmission',
        'iddimension',
        'year',
        'chassis',
        'engine',
        'interior',
        'exterior',
        'condition',
        'mileage',
        'idfuel',
        'date',
        'timestamp',
        'price',
        'fob',
        'reservecountry',
        'cylinder',
        'status',
        'hide',
        'original',
        'website',
        'webstatus'
	];


    // foregin keys in stock
    public function maker()
    {
        return $this->hasOne('App\Models\Vehicle\Maker', "idmaker", "idmaker");
    }
    public function model()
    {
        return $this->hasOne('App\Models\Vehicle\Models', "idmodel", "idmodel");
    }
    public function code()
    {
        return $this->hasOne('App\Models\Vehicle\Code', "idcode", "idcode");
    }
    public function package()
    {
        return $this->hasOne('App\Models\Vehicle\Package', "idpackage", "idpackage");
    }
    public function body()
    {
        return $this->hasOne('App\Models\Vehicle\Vehiclebody', "idvehiclebody", "idvehiclebody");
    }
    public function transmission()
    {
        return $this->hasOne('App\Models\Vehicle\Transmission', "idtransmission", "idtransmission");
    }
    public function dimension()
    {
        return $this->hasOne('App\Models\Vehicle\Dimension', "iddimension", "iddimension");
    }
    public function fuel()
    {
        return $this->hasOne('App\Models\Vehicle\Fuel', "idfuel", "idfuel");
    }



    // belongs to

    //
    public function image()
    {
        return $this->belongsTo('App\Models\Stock\Image', "idstock", "idstock");
    }
    public function images() {
        return $this->hasMany('App\Models\Stock\Image', 'idstock', 'idstock');
    }
    public function auction()
    {
        return $this->belongsTo('App\Models\Stock\Auction', "idstock", "idstock");
    }
    public function country()
    {
        return $this->belongsTo('App\Models\Stock\StockCountry', "idstock", "idstock");
    }
    public function inspection()
    {
        return $this->belongsTo('App\Models\Stock\Inspection', "idstock", "idstock");
    }
    public function makes(){
        return $this->belongsTo('App\Models\Vehicle\Maker');
    }
    public function shipment(){
        return $this->belongsTo('App\Models\Stock\Shipment', "idstock", "idstock");
    }

    public function order(){
        return $this->belongsTo('App\Models\Order\Order', "idstock", "idstock");
    }



}
