<?php

namespace App;

/**
 * App\MessageContact
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $message_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\MessageContact whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\MessageContact whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\MessageContact whereMessageId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\MessageContact whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\MessageContact whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property integer $contact_id
 * @method static \Illuminate\Database\Query\Builder|\App\MessageContact whereContactId($value)
 * @property-read \App\Contact $contact
 */
class MessageContact extends Model
{
    protected $fillable = [
      'message_id',
      'contact_id',
    ];
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
