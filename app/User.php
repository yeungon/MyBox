<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'masterkey', 'publickey'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /*Customize tính năng gửi email khôi phục mật khẩu
    https://thewebtier.com/laravel/modify-password-reset-email-text-laravel/

    https://tutorials.kode-blog.com/laravel-authentication-with-password-reset
    https://laravel.com/docs/5.5/passwords

    */
    /*Hàm này sẽ ghi đè hàm gửi email*/
    public function sendPasswordResetNotification($token)
    {
        /*Có thể pass $this->usename hoặc $this->email trong notify, tham khảo
            https://stackoverflow.com/questions/40703804/laravel-5-3-how-to-show-username-in-notifications-email
        */
        $this->notify(new Notifications\MailResetPasswordNotification($token, $this->email, $this->username));
    }


    /*ràng buộc mối quan hệ với một - nhiều với message*/
    
    public function message (){

        return $this->hasMany('App/message', 'usersid', 'id');

    }

    /*ràng buộc mối quan hệ với một - nhiều với ask*/
    
    public function ask (){

        return $this->hasMany('App/ask', 'usersid', 'id');

    }


}
