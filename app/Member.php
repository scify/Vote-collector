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
     * Function that returns the default answer of this member,
     * for a specified voting and voting item, based on the
     * first group that they are in
     *
     * @param $votingId     The id of the voting
     * @param $votingItemId The id of the votingItem
     * @return int          The id of the answer
     */
    public function groupAnswer($votingId, $votingItemId) {
        // Check if member is in ANY group
        if ($this->groups()->count() > 0) {
            $firstGroup = $this->groups()->firstOrFail();   // Get the first group

            // Get the group vote of the above group in the specified voting and get the answer id
            $answerId = GroupVote::where([
                'voting_id' => $votingId,
                'voting_item_id' => $votingItemId,
                'group_id' => $firstGroup->id
            ])->first()->answer->id;

            // If there was an answer for this group, voting and voting item, return it (otherwise will be null)
            return $answerId;
        }

        return null;    // If the member isn't in any groups, return null so the first answer is selected
    }

    /**
     * Returns the id of the answer that this member voted for in a specified voting and for a specific voting item,
     * or null if the member hasn't voted on that voting at all yet.
     *
     * @param $votingId     The id of the voting
     * @param $votingItemId The id of the votingItem
     * @return int          VoteTypeAnswer id!
     */
    public function vote($votingId, $votingItemId) {
        $vote = $this->votes()->where([
            'voting_id' => $votingId,
            'voting_item_id' => $votingItemId
        ])->first();

        if ($vote != null) {
            if ($vote->answer != null) {
                return $vote->answer->id;
            } else {
                return '';  // Return empty string to show that the member was saved as absent
            }
        }
        return null;
    }
}