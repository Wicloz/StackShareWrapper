<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\AuthToken
 *
 * @property int $id
 * @property string $token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\AuthToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AuthToken whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AuthToken whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AuthToken whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AuthToken extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['token'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['token'];

    /**
     * @param $value
     */
    public function setTokenAttribute($value)
    {
        $this->attributes['token'] = bcrypt($value);
    }
}
