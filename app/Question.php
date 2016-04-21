<?php

namespace DataCollection;

use Illuminate\Database\Eloquent\Model;

/**
 * DataCollection\Question
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $question
 * @property integer $campaign_id
 * @property integer $order
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\Question whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\Question whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\Question whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\Question whereQuestion($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\Question whereCampaignId($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\Question whereOrder($value)
 * @mixin \Eloquent
 */
class Question extends Model
{
    protected $fillable = [
        'question',
        'order'
    ];

    protected $visible = [
        'question',
        'order'
    ];

    public function __construct($question = '')
    {
        $this->question = $question;
    }
}
