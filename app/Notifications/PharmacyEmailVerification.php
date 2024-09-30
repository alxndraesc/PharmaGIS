<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class PharmacyEmailVerification extends Notification
{
    use Queueable;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    // Define the channels to be used
    public function via($notifiable)
    {
        return ['mail']; // Send the notification via email
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verify Your Pharmacy Email Address')
            ->line('Please click the button below to verify your email address.')
            ->action('Verify Email Address', $this->verificationUrl($notifiable))
            ->line('Thank you for using our application!');
    }

    protected function verificationUrl($notifiable)
{
    $url = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
    );

    \Log::info('Verification URL: ' . $url); // Log the verification URL
    return $url;
}

}

