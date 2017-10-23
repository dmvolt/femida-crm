<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MyFile extends Model
{
    protected $appends = ['text'];

    public function getTextAttribute()
    {
        return $this->original_filename;
    }

}
