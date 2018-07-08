<?php

namespace App;

/**
 * App\Notice
 *
 * @property integer $id
 * @property string $title
 * @property string $subject
 * @property string $text
 * @property string $type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Notice whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Notice whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Notice whereSubject($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Notice whereText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Notice whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Notice whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Notice whereUpdatedAt($value)
 */
class Notice extends Model
{
	public static $types = [
        'sms',
        'email'
    ];

    public static $typeNames = [
        'sms' => 'SMS',
        'email' => 'Email',
    ];
	
	public static $variables = [
        '{address}',
        '{date}',
		'{managerName}',
		'{managerPosition}',
		'{managerPhone}',
    ];
	
	public static $templateVariables = [
        '{address}' => 'Адрес',
        '{date}' => 'Дата и время',
		'{managerName}' => 'Менеджер',
		'{managerPosition}' => 'Должность',
		'{managerPhone}' => 'Телефон',
    ];
}
