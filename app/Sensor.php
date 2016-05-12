<?php

namespace DataCollection;

use Illuminate\Database\Eloquent\Model;

/**
 * DataCollection\Sensor
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $name
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\Sensor whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\Sensor whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\Sensor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\Sensor whereName($value)
 * @mixin \Eloquent
 * @property integer $type
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\Sensor whereType($value)
 */
class Sensor extends Model
{
    protected $fillable = [
        'name',
        'type'
    ];

    protected $visible = [
        'name',
        'type'
    ];
}
