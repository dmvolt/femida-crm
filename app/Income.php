<?php

namespace App;

use Carbon\Carbon;

/**
 * App\Income
 *
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Income whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Income whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Income whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Income whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $color
 * @method static \Illuminate\Database\Query\Builder|\App\Income whereColor($value)
 */
class Income extends Model
{

    public static $colors = [
        '#28a745',
        '#007bff',
		'#17a2b8',
        '#ffc107',
        '#dc3545',
		'#868e96',
		'#343a40',
    ];

    public static $colorNames = [
        '#28a745' => 'Зеленый',
        '#007bff' => 'Синий',
		'#17a2b8' => 'Голубой',
        '#ffc107' => 'Желтый',
        '#dc3545' => 'Красный',
		'#868e96' => 'Серый',
		'#343a40' => 'Черный',
    ];

    public function identifiableName()
    {
        return $this->name;
    }
}