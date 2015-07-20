<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    public function members() {
        return $this->hasMany('App\Member');
    }
}
