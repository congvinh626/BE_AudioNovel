<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Otp;

class EmailVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $message;
    public $subject;
    public $fromEmail;
    public $mailer;
    public $otp;
    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        $this->message = 'Vui lòng nhập otp để xác thực tài khoản';
        $this->subject = 'Xác thực tài khoản ';
        $this->fromEmail = 'fromEmail';
        $this->mailer = 'mailer';
        $this->otp = new Otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // dd($notifiable);

        $otp = $this->otp->generate($notifiable->email, 6, 60);

        return (new MailMessage)
            ->mailer('smtp')
            ->subject($this->subject . $notifiable->username)
            ->greeting($this->subject)
            ->line($this->message)
            ->line('Mã OTP: '.$otp->token);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
