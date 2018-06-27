<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ask extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ask', 'usersid', 'replyid', 'publish'
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

  /*https://toidicode.com/cac-moi-quan-he-relationships-trong-eloquent-14.html*/
  public function reply()
  {
    return $this->hasOne('App\Reply', 'asksid', 'id');
  }


}
