<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = ['first_name', 'last_name', 'district_id'];

    public function groups() {
        return $this->belongsToMany('App\Group');
    }

    public function votes() {
        return $this->hasMany('App\Vote');
    }

    public function district() {
        return $this->belongsTo('App\District');
    }

    /**
     * Get a list of group ids that this member is in
     *
     * @return array
     */
    public function getGroupListAttribute() {
        return $this->groups->lists('id');
    }

    /**
     * Function that returns the default answer of this member, based
     * on the first group that they are in
     *
     * @param $votingId The id of the voting to return an answer for
     * @return int      The id of the answer
     */
    public function groupAnswer($votingId) {
        // Check if member is in ANY group
        if ($this->groups()->count() > 0) {
            $firstGroup = $this->groups()->firstOrFail();   // Get the first group

            // Get the group vote of the above group in the specified voting and get the answer id
            $answerId = GroupVote::where([
                'group_id' => $firstGroup->id,
                'voting_id' => $votingId
            ])->get()->first()->answer->id;

            // If there was an answer for this group and this voting, return it
            // (otherwise will be null and the 1st answer will be selected by the form)
            return $answerId;
        }

        return null;    // If the member isn't in any groups, return null so the first answer is selected
    }
}