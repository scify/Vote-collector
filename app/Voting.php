<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voting extends Model
{
    protected $fillable = ['title', 'completed'];

    // voting items relation
    public function votingItems() {
        return $this->hasMany('App\VotingItem');
    }

    // votes of voting
    public function votes() {
        return $this->hasMany('App\Vote');
    }

    // Returns true if this voting has default votes set for all the groups that exist
    public function defaultVotesSet() {
        if (GroupVote::where('voting_id', '=', $this->id)->count() == Group::all()->count()) {
            return true;
        }

        return false;
    }

}