<?php

namespace App\Notifications;

use App\Models\Risk;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RiskAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly Risk $risk,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Risk Assigned: {$this->risk->title}")
            ->greeting("Hello {$notifiable->name},")
            ->line("You have been assigned as the owner of a {$this->risk->risk_level} risk.")
            ->line("**Title:** {$this->risk->title}")
            ->line("**Score:** {$this->risk->risk_score} (L{$this->risk->likelihood} × I{$this->risk->impact})")
            ->action('View Risk', route('risks.show', $this->risk))
            ->line('Please review and plan appropriate treatment.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'      => 'risk_assigned',
            'risk_id'   => $this->risk->id,
            'title'     => $this->risk->title,
            'risk_level'=> $this->risk->risk_level,
            'url'       => route('risks.show', $this->risk),
        ];
    }
}