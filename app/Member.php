<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = ['first_name', 'last_name'];

    public function groups() {
        return $this->belongsToMany('App\Group');
    }

    public function votes() {
        return $this->hasMany('App\Vote');
    }

    /**
     * Get a list of group ids that this member is in
     *
     * @return array
     */
    public function getGroupListAttribute() {
        return $this->groups->lists('id');
    }
}