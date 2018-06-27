<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reply', 'asksid', 'usersid',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ]; //

   public function user()
   {

    return $this->belongsTo('App\User', 'usersid', 'id');

   }

     // public function ask()
     // {

     //  return $this->belongsTo('App\Ask', 'replyid', 'id');

     // }


}
