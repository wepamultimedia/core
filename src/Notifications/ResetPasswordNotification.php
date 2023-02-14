<?php

namespace Wepa\Core\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    protected string $email;

    protected string $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $token, string $email)
    {
        $this->token = $token;
        $this->email = $email;
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

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting(trans('core::auth.reset_password_email_greeting'))
            ->salutation('')
            ->line(trans('core::auth.reset_password_email_summary_1'))
            ->action(trans('core::auth.reset_password'),
                route('password.reset', ['token' => $this->token, 'email' => $this->email]))
            ->line(trans('core::auth.reset_password_email_summary_2'))
            ->salutation(new HtmlString(trans('core::auth.reset_password_email_saludation').',<br><strong>'.config('app.name').'</strong>'));
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
}
