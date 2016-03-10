<?php

namespace DataCollection;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * DataCollection\User
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
