<?php

namespace App;

use Carbon\Carbon;

/**
 * App\Cost
 *
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Cost whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Cost whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Cost whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Cost whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $type
 * @property string $color
 * @method static \Illuminate\Database\Query\Builder|\App\Cost whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Cost whereColor($value)
 */
class Cost extends Model
{
	
    public static $types = [
        'reklama',
        'zp',
        'office',
		'other',
    ];

    public static $typeNames = [
        'reklama' => 'Реклама',
        'zp' => 'Заработная плата',
        'office' => 'Офис',
		'other' => 'Прочие',
    ];

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
	
	public static function allForSelect()
    {
        $cost_arr_for_select = [];

		if($costs_result = Cost::all()){
			foreach($costs_result as $value){
				$cost_arr_for_select[self::$typeNames[$value->type]][$value->id] = $value->name;
			}
		}
        return $cost_arr_for_select;
    }

    public static function getDefaultCost()
    {
        $firstDefaultCost = Cost::whereType('other')->first();

        if ( $firstDefaultCost )
        {
            return $firstDefaultCost->id;
        }
        return 0;
    }

    public function identifiableName()
    {
        return $this->name;
    }
}