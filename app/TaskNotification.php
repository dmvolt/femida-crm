<?php

namespace App;

/**
 * App\TaskNotification
 *
 * @property integer $id
 * @property integer $task_id
 * @property string $text
 * @property string $created
 * @property string $datetime
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Task $task
 * @method static \Illuminate\Database\Query\Builder|\App\TaskNotification whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TaskNotification whereTaskId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TaskNotification whereText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TaskNotification whereCreated($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TaskNotification whereDatetime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TaskNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TaskNotification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TaskNotification extends Model
{

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function setCreated()
    {
        $this->created = 'yes';
        $this->save();
    }
}
