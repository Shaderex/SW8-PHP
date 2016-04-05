<?php

namespace DataCollection;

use Illuminate\Database\Eloquent\Model;

/**
 * DataCollection\Snapshot
 *
 * @mixin \Eloquent
 */
class Snapshot extends Model
{
    protected $fillable = [
        'sensor_data_json'
    ];

}
