<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    protected $revisionNullString = '-';
    protected $revisionUnknownString = '-';

    protected function getDateFormatted($value)
    {
        if ( $value )
        {
            return Carbon::parse($value)->format('Y-m-d H:i');
        }
    }

    public function getRevisionUnknownString()
    {
        return '[удалено]';
    }

    // @todo: refactor
    public static function getRuName($className)
    {
        switch ( $className )
        {
            case 'App\Contact' : return 'Контакт';
            case 'App\Task' : return 'Объект "Задача"';
            case 'App\Lead' : return 'Объект "Сделка"';

            default: return 'Объект';
        }
    }

    public function getCreatedAtAttribute($value)
    {
        return $this->getDateFormatted($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return $this->getDateFormatted($value);
    }

    public function phone($phone = null)
    {
        $mask = '#';

        $format = array(
            '7' => '###-##-##',
            '10' => '+7 (###) ### ####',
            '11' => '# (###) ### ####'
        );

        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (is_array($format)) {
            if (array_key_exists(strlen($phone), $format)) {
                $format = $format[strlen($phone)];
            } else {
                return false;
            }
        }

        $pattern = '/' . str_repeat('([0-9])?', substr_count($format, $mask)) . '(.*)/';

        $format = preg_replace_callback(
            str_replace('#', $mask, '/([#])/'),
            function () use (&$counter) {
                return '${' . (++$counter) . '}';
            },
            $format
        );

        return ($phone) ? trim(preg_replace($pattern, $format, $phone, 1)) : false;
    }
}
