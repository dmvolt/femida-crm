<?php

namespace App;

/**
 * App\ContactActivity
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property string $type
 * @property integer $contact_id
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\ContactActivity whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ContactActivity whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ContactActivity whereContactId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ContactActivity whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ContactActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ContactActivity whereUpdatedAt($value)
 * @property string $text
 * @method static \Illuminate\Database\Query\Builder|\App\ContactActivity whereText($value)
 * @property-read \App\User $user
 */
class ContactActivity extends Model
{
    const TYPES = [
        'update' => 'Обновленны данные',
        'task' => 'Создана задача',
        'lead' => 'Создана сделка',
        'comment' => 'Добавлен комментарий'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

}
