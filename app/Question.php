<?php

namespace DataCollection;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question',
        'order'
    ];
}
