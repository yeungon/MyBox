<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MailResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *@see  https://tutorials.kode-blog.com/laravel-authentication-with-password-reset
     *@see https://thewebtier.com/laravel/modify-password-reset-email-text-laravel/
     *
     * @return void
     */

    public $token;

    public $email;

    public $username;
    
    /*pass từ sendPasswordResetNotification trong Model User*/
    public function __construct($token, $email, $username)
    {
        $this->token = $token;
        $this->email = $email;
        $this->username = $username;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        
        $link = url( "/password/reset/?token=" . $this->token );

        return (new MailMessage)
                    // ->line('The introduction to the notification.')
                    // ->action('Notification Action', url('/'))
                    // ->line('Thank you for using our application!');

                    ->line('You are receiving this email because we received a password reset request for your account.')
                    ->action('Reset Password', route('password.reset.token',['token' => $this->token]))
                    ->line('If you did not request a password reset, no further action is required.');

                  // ->view('reset.emailer')
                  // ->from('info@example.com')
                  // ->subject( 'Reset your password' )
                  // ->line( "Hey, We've successfully changed the text " )
                  // ->action( 'Reset Password', $link )
                  // ->attach('reset.attachment')
                  // ->line( 'Thank you!' );

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
