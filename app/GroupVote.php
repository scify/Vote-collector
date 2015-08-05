<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupVote extends Model {

	public $timestamps = false;
    protected $fillable = ['voting_id', 'voting_item_id', 'group_id', 'answer_id'];

    public function voting() {
        return $this->belongsTo('App\Voting');
    }

    public function votingItem() {
        return $this->belongsTo('App\VotingItem');
    }

    public function group() {
        return $this->belongsTo('App\Group');
    }

    public function answer() {
        return $this->belongsTo('App\VoteTypeAnswer', 'answer_id');
    }

}
