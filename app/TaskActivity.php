<?php

namespace App;

/**
 * App\TaskActivity
 *
 * @property integer $id
 * @property string $type
 * @property string $text
 * @property integer $lead_id
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\TaskActivity whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TaskActivity whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TaskActivity whereText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TaskActivity whereLeadId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TaskActivity whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TaskActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TaskActivity whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property integer $task_id
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\TaskActivity whereTaskId($value)
 */
class TaskActivity extends Model
{
    const TYPES = [
        'update' => 'Обновленны данные',
        'comment' => 'Добавлен комментарий',
        'completed' => 'Отмечено выполнение задачи'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

}
