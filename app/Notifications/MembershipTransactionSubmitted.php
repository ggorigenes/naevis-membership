<?php

namespace App\Notifications;

use App\Models\MembershipTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MembershipTransactionSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public MembershipTransaction $transaction)
    {
        $this->afterCommit();
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $member = $this->transaction->member;

        return (new MailMessage)
            ->subject('Nævis Membership Transaction Received')
            ->greeting('Hello ' . $member->first_name . ' ' . $member->last_name . ',')
            ->line('We received your membership ' . strtolower($this->transaction->transaction_type) . '.')
            ->line('Reference number: ' . $this->transaction->reference_number)
            ->line('Level: ' . $this->transaction->level->name . ' (₱' . number_format($this->transaction->amount, 2) . ')')
            ->line('Status: ' . $this->transaction->status)
            ->line('Keep this reference number for your records.')
            ->salutation('Nævis Membership System');
    }
}