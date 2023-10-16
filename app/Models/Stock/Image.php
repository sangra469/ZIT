<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images';
    protected $primarykey = 'idimages';
    public $timestamps = true;


    protected $fillable = [
		'idstock', 'image'
	];


    // foregin keys in code
    public function stock()
    {
        return $this->hasOne('App\Models\Stock\Stock', "idstock", "idstock");
    }








}
