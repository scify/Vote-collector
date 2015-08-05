<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voting extends Model
{
    protected $fillable = ['title', 'completed'];

    // votes relation
    public function votes() {
        return $this->hasMany('App\Vote');
    }

    // voting items relation
    public function votingItems() {
        return $this->hasMany('App\VotingItem');
    }

    // Returns true if this voting has default votes set for all the groups that exist
    public function defaultVotesSet() {
        // check that the count of GroupVotes with this voting's id is equal to the count of all groups * number of voting items of this voting
        $groupCount = Group::all()->count();
        $votingItemCount = VotingItem::ofVoting($this->id)->count();

        $correctAmount = $groupCount * $votingItemCount;

        if (GroupVote::ofVoting($this->id)->count() == $correctAmount) {
            return true;
        }

        return false;
    }

}