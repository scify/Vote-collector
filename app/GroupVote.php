<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupVote extends Model {

	public $timestamps = false;
    protected $fillable = ['voting_id', 'group_id', 'answer_id'];

    public function voting() {
        return $this->belongsTo('App\Voting');
    }

    public function group() {
        return $this->belongsTo('App\Group');
    }

    public function answer() {
        return $this->belongsTo('App\VoteTypeAnswer', 'answer_id');
    }

}
