<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PharmacyApproved extends Notification
{
    use Queueable;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Pharmacy Account Has Been Approved')
            ->greeting('Hello ' . $this->user->name . ',')
            ->line('We are pleased to inform you that your pharmacy account has been approved.')
            ->line('You can now access the pharmacy dashboard and manage your listings.')
            ->action('Log in to your account here', url('/'))
            ->line('Thank you for your patience and for being part of our platform.');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}