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
 * @property-read \Illuminate\Database\Eloquent\Collection|\DataCollection\Question[] $questions
 * @property-read \Illuminate\Database\Eloquent\Collection|\DataCollection\Snapshot[] $snapshots
 * @property-read \Illuminate\Database\Eloquent\Collection|\DataCollection\Participant[] $participants
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
        'campaign_length',
        'questionnaire_placement',
        'measurements_per_sample',
        'sample_delay',
        'samples_per_snapshot'
    ];

    protected $visible = [
        'id',
        'name',
        'description',
        'is_private',
        'snapshot_length',
        'sample_duration',
        'sample_frequency',
        'measurement_frequency',
        'sensors',
        'questions',
        'campaign_length',
        'questionnaire_placement',
        'user'
    ];

    protected $appends = [
        'user_name'
    ];

    public static $placements = [
        0 => 'end',
        1 => 'start'
    ];

    /**
     * @param $isPrivate
     */
    public function setIsPrivateAttribute($isPrivate)
    {
        $this->attributes['is_private'] = $isPrivate ? true : false;
    }

    public function getUserNameAttribute()
    {
        return $this->user()->get('name');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sensors()
    {
        return $this->belongsToMany(Sensor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function snapshots()
    {
        return $this->hasMany(Snapshot::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function participants()
    {
        return $this->belongsToMany(Participant::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
