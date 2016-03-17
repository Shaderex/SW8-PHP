<?php

namespace DataCollection;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * DataCollection\Campaign
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $name
 * @property string $description
 * @property boolean $is_private
 * @property integer $snapshot_length
 * @property integer $sample_duration
 * @property integer $sample_frequency
 * @property integer $measurement_frequency
 * @method static Builder|Campaign whereId($value)
 * @method static Builder|Campaign whereCreatedAt($value)
 * @method static Builder|Campaign whereUpdatedAt($value)
 * @method static Builder|Campaign whereName($value)
 * @method static Builder|Campaign whereDescription($value)
 * @method static Builder|Campaign whereIsPrivate($value)
 * @method static Builder|Campaign whereSnapshotLength($value)
 * @method static Builder|Campaign whereSampleDuration($value)
 * @method static Builder|Campaign whereSampleFrequency($value)
 * @method static Builder|Campaign whereMeasurementFrequency($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\DataCollection\Sensor[] $sensors
 */
class Campaign extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_private',
        'snapshot_length',
        'sample_duration',
        'sample_frequency',
        'measurement_frequency',
    ];

    /**
     * @param $isPrivate
     */
    public function setIsPrivateAttribute($isPrivate)
    {
        $this->attributes['is_private'] = $isPrivate ? true : false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sensors()
    {
        return $this->belongsToMany(Sensor::class);
    }
}
