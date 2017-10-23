<?php

namespace App;


use Illuminate\Support\Collection;

class Folder extends Model
{
    protected $appends = ['text', 'children'];

    public function folders()
    {
        return $this->hasMany('App\Folder', 'parent_id', 'id');
    }

    public function myFiles()
    {
        return $this->hasMany('App\MyFile');
    }

    public function getChildrenAttribute()
    {
        $collect = new Collection();
        foreach ($this->folders as $_folder)
        {
            $collect->push($_folder);
        }

        foreach ($this->myFiles as $_file)
        {
            $file = collect(['text' => $_file->original_filename, 'icon' => 'fa fa-file-text-o']);
            $collect->push($file);
        }
        return $collect;
    }

    public function getTextAttribute()
    {
        return $this->name;
    }
}
