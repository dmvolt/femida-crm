<?php

namespace App;

/**
 * App\LeadActivity
 *
 * @property integer $id
 * @property string $type
 * @property string $text
 * @property integer $lead_id
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\LeadActivity whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LeadActivity whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LeadActivity whereText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LeadActivity whereLeadId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LeadActivity whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LeadActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LeadActivity whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\User $user
 */
class LeadActivity extends Model
{
    const TYPES = [
        'update' => 'Обновленны данные',
        'task' => 'Создана задача',
        'comment' => 'Добавлен комментарий'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

}
