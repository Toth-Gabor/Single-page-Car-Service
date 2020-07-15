<?php

namespace App;

class Car extends BaseModel
{
    protected $primaryKey = ['client_id', 'car_id'];
    protected $table = 'cars';
    protected $fillable =
        [
            'client_id',
            'car_id',
            'type',
            'registered',
            'ownbrand',
            'accident'
        ];
}
