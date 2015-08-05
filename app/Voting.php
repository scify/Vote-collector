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

    // Returns true if this voting has default votes set for all the groups that exist
    public function defaultVotesSet() {
        // todo: this is broken
        /*if (GroupVote::where('voting_id', '=', $this->id)->count() == Group::all()->count()) {
            return true;
        }*/

        $votingItems = $this->votingItems();


        return false;
    }

}