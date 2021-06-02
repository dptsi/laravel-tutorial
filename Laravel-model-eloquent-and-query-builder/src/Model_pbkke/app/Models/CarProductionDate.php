<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarProductionDate extends Model
{
    use HasFactory;
    
    // public function carmodel(){
    //     return $this->hasOne(CarModel::class);

    // }

    public function carmodel(){
        return $this->belongsTo(CarModel::class, 'model_id');
    }

}
