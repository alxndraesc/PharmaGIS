<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Pharmacy;

class PharmacyRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $pharmacy;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Pharmacy  $pharmacy
     * @return void
     */
    public function __construct(Pharmacy $pharmacy)
    {
        $this->pharmacy = $pharmacy;
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
        return (new MailMessage)
                    ->subject('Pharmacy Registration Rejected')
                    ->greeting('Hello ' . $this->pharmacy->user->name . ',')
                    ->line('We regret to inform you that your pharmacy registration request has been rejected.')
                    ->line('If you believe this is a mistake or you would like to try again, please log in to your account and re-upload the required documents.')
                    ->line('Alternatively, you may choose to void your registration if you no longer wish to continue.')
                    ->action('Re-upload Documents', url('/pharmacy/resubmit-documents'))
                    ->line('Thank you for using PharmaGIS.');
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
            'pharmacy_id' => $this->pharmacy->id,
            'message' => 'Your pharmacy registration has been rejected.'
        ];
    }
}
