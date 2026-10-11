<?php

namespace App\Notifications;

use App\Models\RiskTreatmentPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TreatmentDueNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly RiskTreatmentPlan $plan,
        public readonly bool $overdue = false,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->overdue
            ? "OVERDUE: Treatment Plan #{$this->plan->id}"
            : "Treatment Plan Assigned #{$this->plan->id}";

        return (new MailMessage)
            ->subject($subject)
            ->greeting("Hello {$notifiable->name},")
            ->line($this->overdue
                ? "This treatment plan is overdue."
                : "A treatment plan has been assigned to you.")
            ->line("**Strategy:** {$this->plan->strategy}")
            ->line("**Action:** {$this->plan->action}")
            ->line("**Due:** " . ($this->plan->due_date?->toDateString() ?? 'Not set'))
            ->action('View Risk', route('risks.show', $this->plan->risk_id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'      => $this->overdue ? 'treatment_overdue' : 'treatment_assigned',
            'plan_id'   => $this->plan->id,
            'risk_id'   => $this->plan->risk_id,
            'strategy'  => $this->plan->strategy,
            'due_date'  => $this->plan->due_date?->toDateString(),
            'url'       => route('risks.show', $this->plan->risk_id),
        ];
    }
}