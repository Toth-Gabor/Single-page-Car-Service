<?php

namespace App;

class Client extends BaseModel
{
    protected $primaryKey = 'id';
    protected $table = 'clients';
    protected $fillable = [ 'id', 'name', 'idcard'];
}
