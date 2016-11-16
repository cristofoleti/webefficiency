<?php

namespace Webefficiency;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    /**
     * fillable fields
     *
     * @var array
     */
    protected $fillable = ['group_id', 'fieldlogger_id', 'name', 'active', 'is_admin'];

    /**
     * @return boolean
     */
    public function isDefault()
    {
        return intval($this->attributes['default']);
    }

    /**
     * Get company name with group name
     * @return string
     */
    public function getNameWithGroup()
    {
        $groupName = $this->group->name;
        $companyName = $this->attributes['name'];
        return "{$groupName}/{$companyName}";
    }

    /**
     * Get the related group
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Return related users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get company related readings
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function readings()
    {
        return $this->hasMany(Reading::class);
    }

    /**
     * Get latest reading for the active company
     *
     * @return Reading
     */
    public function getLatestReading()
    {
        return $this->readings()->orderBy('date', 'desc')->orderBy('time', 'desc')->first();
    }

}
