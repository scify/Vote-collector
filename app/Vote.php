<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    public $timestamps = false;

    public function voting() {
        return $this->belongsTo('App\Voting');
    }

    public function member() {
        return $this->belongsTo('App\Member');
    }

    public function answer() {
        return $this->belongsTo('App\VoteTypeAnswer', 'answer_id');
    }
}