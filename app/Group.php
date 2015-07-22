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


    /**
     * Returns this group's default answer id for
     * the specified voting
     *
     * @param $voting_id    Id of voting
     * @return mixed        The id of the group vote
     */
    public function defaultAnswer($voting_id) {
        $gv = GroupVote::where([
            'voting_id' => $voting_id,
            'group_id' => $this->id
        ])->first();    // there should only be one

        return $gv->answer_id;
    }
}