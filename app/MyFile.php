<?php

namespace App;

class MyFile extends Model
{
    protected $appends = ['text'];

    public function getTextAttribute()
    {
        return $this->original_filename;
    }
}
