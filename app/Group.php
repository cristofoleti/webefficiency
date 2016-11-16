<?php

namespace Webefficiency;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['name', 'active'];

    /**
     * Get related companies
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companies()
    {
        return $this->hasMany(Company::class);
    }
    
      

}
