<?php

namespace DataCollection;

use Illuminate\Database\Eloquent\Model;

/**
 * DataCollection\Snapshot
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property string $sensor_data_json
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $campaign_id
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\Snapshot whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\Snapshot whereSensorDataJson($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\Snapshot whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\Snapshot whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\Snapshot whereCampaignId($value)
 */
class Snapshot extends Model
{
    protected $fillable = [
        'sensor_data_json'
    ];

}
