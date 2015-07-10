<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function members() {
        return $this->belongsToMany('App\Member');
    }

    /**
     * Get a list of member ids that are in this group
     *
     * @return array
     */
    public function getMemberListAttribute() {
        return $this->members->lists('id');
    }
}