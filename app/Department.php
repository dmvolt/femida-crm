<?php

namespace App;

/**
 * App\Department
 *
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Department whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Department whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Department whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Department whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $address
 * @method static \Illuminate\Database\Query\Builder|\App\Department whereAddress($value)
 */
class Department extends Model
{
    //
}
