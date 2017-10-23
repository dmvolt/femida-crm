<?php

namespace App;

/**
 * App\ServicePayment
 *
 * @property integer $id
 * @property integer $service_id
 * @property integer $cost
 * @property integer $bonus
 * @property integer $days
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\ServicePayment whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ServicePayment whereServiceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ServicePayment whereCost($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ServicePayment whereBonus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ServicePayment whereDays($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ServicePayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ServicePayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ServicePayment extends Model
{
    //
}
