<?php

namespace App;

class Service extends BaseModel
{
    protected $primaryKey = ['client_id', 'car_id', 'lognumber'];
    public $incrementing = false;
    protected $table = 'services';
    protected $fillable =
        [
            'client_id',
            'car_id',
            'lognumber',
            'event',
            'eventtime',
            'document_id'
        ];
}
