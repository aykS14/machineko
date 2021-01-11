<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
// use Illuminate\Auth\Notifications\ResetPassword; // ResetPasswordクラス参照

class PasswordResetMultiLang extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    /**
     * Create a new notification instance.
     * 
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
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
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        $mailMessage = new MailMessage;

        return $mailMessage
                    // ->from('admin@example.com', config('app.name'))
                    ->subject(Lang::getFromJson('email_message.Mail.password_reset.title'))
                    // ->line('The introduction to the notification.')
                    ->line(Lang::getFromJson('email_message.Mail.password_reset.line'))
                    // ->action('Notification Action', url('/'))
                    ->action(Lang::getFromJson('email_message.Mail.password_reset.action'), url(config('app.url').route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false)))
                    // ->action('パスワード再設定', url(config('app.url').route('password.reset', $this->token, false)))
                    // ->line('Thank you for using our application!');
                    ->line(Lang::getFromJson('email_message.Mail.password_reset.out_line1', ['count' => config('auth.passwords.users.expire')]))
                    ->line(Lang::getFromJson('email_message.Mail.password_reset.out_line2'));
                    // ->line('本メールにお心当たりがない場合は、本メールを破棄してください。');
    }
    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
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
