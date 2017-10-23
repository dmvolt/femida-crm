<?php

namespace App\Rapyd\Fields;

use Carbon\Carbon;
use Zofe\Rapyd\DataForm\Field\Daterange;
use Zofe\Rapyd\DataForm\Field\Field;

class MyDateRange extends Daterange
{

    public function getNewValue()
    {
        Field::getNewValue();
        $this->values = explode($this->serialization_sep, $this->new_value);
        foreach ($this->values as $value) {
            $values[] = $this->humanDateToIso($value);
        }
		
        if ( $values[1] != null )
        {
            $datetime = Carbon::parse($values[1]);
            $datetime->addHours(24)->addMinute(59);
            $values[1] = $datetime->format('Y-m-d');
        }
		

        if (isset($values)) {
            $this->new_value = implode($this->serialization_sep, $values);
        }
    }

}