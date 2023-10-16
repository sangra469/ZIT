<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userrole extends Model
{
    use HasFactory;

    protected $table = 'userrole';



    public function user()
    {
        return $this->belongsTo('App\MOdels\User', "iduserrole", "iduserrole");
    }


}
