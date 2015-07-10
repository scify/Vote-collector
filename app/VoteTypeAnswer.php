<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoteTypeAnswer extends Model
{
    protected $fillable = ['type', 'answer'];

    public function voteType() {
        return $this->belongsTo('App\VoteType', 'type');
    }

    public function votes() {
        return $this->hasMany('App\Vote', 'answer_id');
    }
}