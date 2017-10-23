<?php

namespace App;

/**
 * App\Expense
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Expense whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Expense whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Expense whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $name
 * @property float $sum
 * @property integer $user_id
 * @property string $file
 * @property-read \App\User $user
 * @property-read mixed $file_url
 * @method static \Illuminate\Database\Query\Builder|\App\Expense whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Expense whereSum($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Expense whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Expense whereFile($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Expense topSearch($value)
 * @property integer $department_id
 * @property-read \App\Department $department
 * @method static \Illuminate\Database\Query\Builder|\App\Expense whereDepartmentId($value)
 */
class Expense extends Model
{
    protected $appends = ['fileUrl'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    public function getFileUrlAttribute($value = null)
    {
        return '<a href="uploads/'.$this->file.'">'.$this->file.'</a>';
    }

    public function scopeTopSearch($query, $value)
    {
        return $query->where(function ($query) use ($value) {
            return $query->where('name','like','%'.$value.'%')
                ->orWhere('sum','like','%'.$value.'%');
        });
    }

}
