<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class VotingItem extends Model
{
    protected $fillable = ['voting_id', 'vote_type_id', 'vote_objective_id'];

    // voting relation
	public function voting() {
        return $this->belongsTo('App\Voting');
    }

    // vote type relation
    public function voteType() {
        return $this->belongsTo('App\VoteType');
    }

    // vote objective relation
    public function voteObjective() {
        return $this->belongsTo('App\VoteObjective');
    }

    // Scope to get all voting items of a specified voting
    public function scopeOfVoting($query, $votingId) {
        return $query->where('voting_id', '=', $votingId);
    }

}
