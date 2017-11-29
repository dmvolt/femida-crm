<?php

namespace App;

/**
 * App\ContactOrigin
 *
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\ContactOrigin whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ContactOrigin whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ContactOrigin whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ContactOrigin whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Contact[] $users
 */
class ContactOrigin extends Model
{
    public function identifiableName()
    {
        return $this->name;
    }

    public function users()
    {
        return $this->hasMany(Contact::class, 'origin_id');
    }
}