<?php

namespace DataCollection;

use Crypt;
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

    protected $hidden = [
        'sensor_data_json',
        'id',
        'created_at',
        'updated_at',
        'campaign_id'
    ];

    protected $appends = [
        'snapshot'
    ];

    public function getSnapshotAttribute()
    {
        return json_decode($this->sensor_data_json);
    }

    public function setSensorDataJsonAttribute($value) {
        $this->attributes['sensor_data_json'] = Crypt::encrypt($value);
    }

    public function getSensorDataJsonAttribute() {
        return Crypt::decrypt($this->attributes['sensor_data_json']);
    }


}
