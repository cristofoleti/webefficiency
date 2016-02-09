<?php

namespace Webefficiency;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{

    protected $fillable = ['active', 'tag', 'description', 'unity'];

    public static function findByTag($tag)
    {
        return self::whereTag($tag)->first();
    }

}
