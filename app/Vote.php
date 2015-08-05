<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    public $timestamps = false;
    protected $fillable = ['voting_id', 'voting_item_id', 'member_id', 'answer_id'];

    public function voting() {
        return $this->belongsTo('App\Voting');
    }

    public function votingItem() {
        return $this->belongsTo('App\VotingItem');
    }

    public function member() {
        return $this->belongsTo('App\Member');
    }

    public function answer() {
        return $this->belongsTo('App\VoteTypeAnswer', 'answer_id');
    }
}