<?php

namespace App;

/**
 * App\Message
 *
 * @property integer $id
 * @property string $name
 * @property string $text
 * @property string $type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $status
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MessageContact[] $users
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereStatus($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MessageContact[] $contacts
 * @property-read mixed $status_name
 */
class Message extends Model
{
    public function contacts()
    {
        return $this->hasMany(MessageContact::class);
    }

    public function getStatusNameAttribute()
    {
        return $this->isCompleted() == 'completed' ? 'Отправлена' : 'Черновик';
    }

    public function isCompleted()
    {
        return $this->status == 'completed';
    }
}
