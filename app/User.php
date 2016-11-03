<?php

namespace Webefficiency;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'is_admin'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token','pivot'];

    /**
     * Return related companies
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }

    /**
     * @return array|static[]
     */
    public function groups()
    {
        return \DB::table('groups')
            ->distinct()
            ->join('companies', 'groups.id', '=', 'companies.group_id')
            ->join('company_user', 'companies.id', '=', 'company_user.company_id')
            ->select(['groups.id', 'groups.name','groups.is_admin'])
            ->where('company_user.user_id', $this->id)
            ->get();
    }

     /**
     * Check if user's group has Admin privilegies
     */
    public function isGroupAdmin()
    {   
        $admin = false;
        $groups = $this->groups();
        foreach ($groups as $group){
            if($group->is_admin) {
                $admin = true;
                break;
            }
        }
        return $admin;

    }

}
