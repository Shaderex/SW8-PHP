<?php

namespace DataCollection;

use Illuminate\Database\Eloquent\Model;

/**
 * DataCollection\Participant
 *
 * @property integer $id
 * @property string $deviceID
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\Participant whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\Participant whereDeviceID($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\Participant whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\Participant whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Participant extends Model
{
    protected $fillable = [
        'device_id'
    ];

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class);
    }
}
