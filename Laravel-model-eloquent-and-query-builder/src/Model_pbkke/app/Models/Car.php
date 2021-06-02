<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    
    protected $table = 'cars';

    protected $primaryKey= 'id';
    // protected $primaryKey= false;
    // protected $timestamps= false;
    // protected $dateFormat= 'h:m:s';

    protected $fillable = ['name','founded','description'];

    // protected $hidden = ['password','remembertoken']; 
    // protected $hidden = ['id','name','founded','description'];
    // protected $visible = ['id'];

    // create methods that will return the right data based on our table
    public function carmodel()
    {
        return $this->hasMany(CarModel::Class);
    } 
    public function headquarter(){
        return $this->hasOne(Headquarter::class);
    }

    // Define a has many through relationship
    public function engine(){
        return $this->hasManyThrough(
                    Engine::class ,
                    CarModel::class,
                    'car_id', // Foreign key on CarModel Table //
                    'model_id' // Foreign key on Engine Table
            );
    }

    // Define a has one through relationship x(wrong) 
    // Define a has many through relationship 
    public function productionDate(){
        return $this->hasManyThrough(
                    CarProductionDate::class ,
                    CarModel::class,
                    'car_id', // Foreign key on CarModel Table //
                    'model_id' // Foreign key 
            );
    }
    
    public function product(){
        return $this->belongsToMany(Product::class);
    }
    

    
}
