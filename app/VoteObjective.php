<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoteObjective extends Model
{
    protected $fillable = ['title', 'description'];

    // voting items relation
    public function votingItems() {
        return $this->hasMany('App\VotingItem');
    }
}