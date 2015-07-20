<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Perifereia extends Model
{
    protected $table = 'perifereies';

    public function members() {
        return $this->hasMany('App\Member', 'perifereia');
    }
}
