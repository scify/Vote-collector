<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voting extends Model
{
    protected $fillable = ['title', 'voting_type', 'objective'];

    // vote type relation
    public function type() {
        return $this->belongsTo('App\VoteType', 'voting_type');
    }

    // vote objective relation
    public function objective() {
        return $this->belongsTo('App\VoteObjective', 'objective');
    }

    // votes of voting
    public function votes() {
        return $this->hasMany('App\Vote');
    }

}