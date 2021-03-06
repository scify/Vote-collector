<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoteType extends Model
{
    public function answers() {
        return $this->hasMany('App\VoteTypeAnswer', 'type');
    }

    // voting items relation
    public function votingItems() {
        return $this->hasMany('App\VotingItem');
    }
}