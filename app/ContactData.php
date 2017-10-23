<?php

namespace App;

use Carbon\Carbon;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * App\ContactData
 *
 * @property integer $id
 * @property string $number
 * @property string $code
 * @property string $issued
 * @property string $address
 * @property string $date
 * @property integer $contact_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\ContactData whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ContactData whereNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ContactData whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ContactData whereIssued($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ContactData whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ContactData whereDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ContactData whereContactId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ContactData whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ContactData whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 */
class ContactData extends Model
{
    protected $table = 'contact_data';

    use RevisionableTrait;

    protected $revisionCreationsEnabled = true;
    protected $revisionFormattedFieldNames = array(
        'number' => 'number',
    );

    public function identifiableName()
    {
        return $this->number;
    }

    public function getDateAttribute($value)
    {
        if ( $value )
        {
            return Carbon::parse($value)->format('Y-m-d');
        }
    }

    public function setDateAttribute($value)
    {
        if ( !$value )
        {
            $value = null;
        }
        return $value;
    }


}
