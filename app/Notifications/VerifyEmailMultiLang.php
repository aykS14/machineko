<?php

namespace App\Notifications;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailMultiLang extends Notification
{
    use Queueable;

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
    public function __construct()
    {
        //
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
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }

        $mailMessage = new MailMessage;
        $mailMessage->greeting = Lang::getFromJson('email_message.Mail.greeting');

        return $mailMessage
        ->subject(Lang::getFromJson('email_message.Mail.Verify.Title'))
        ->line(Lang::getFromJson('email_message.Mail.Verify.Line'))
        ->action(Lang::getFromJson('email_message.Mail.Verify.Action'), $verificationUrl)
        ->line(Lang::getFromJson('email_message.Mail.Verify.OutLine'));
        
        // return (new MailMessage)
        //             ->subject(Lang::getFromJson('本登録メール'))
        //             // ->line('The introduction to the notification.')
        //             ->line(Lang::getFromJson('以下の認証リンクをクリックして本登録を完了させてください。'))
        //             // ->action('Notification Action', url('/'))
        //             ->action(
        //                 Lang::getFromJson('メールアドレスを認証する'),
        //                 $this->verificationUrl($notifiable)
        //             )
        //             // ->line('Thank you for using our application!');
        //             ->line(Lang::getFromJson('このメールに覚えが無い場合は破棄してください。'));
    }

    // /**
    //  * Get the array representation of the notification.
    //  *
    //  * @param  mixed  $notifiable
    //  * @return array
    //  */
    // public function toArray($notifiable)
    // {
    //     return [
    //         //
    //     ];
    // }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify', Carbon::now()->addMinutes(60), ['id' => $notifiable->getKey()]
        );
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
}
