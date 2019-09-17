<?php

namespace App;

use App\Base\BaseModel;


class Role extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key', 'name', 'level'
    ];
    
}