<?php

namespace App;

/**
 * App\Team
 *
 * @property int $id
 * @property int $department_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Team whereDepartmentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Team whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Team whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $name
 * @method static \Illuminate\Database\Query\Builder|\App\Team whereName($value)
 */
class Team extends Model
{
    //
}
