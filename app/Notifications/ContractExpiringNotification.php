<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class ContractExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly Contract $contract, private readonly int $daysRemaining) {}

    public function via(object $notifiable): array { return ['database', 'mail']; }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())->subject('Contrato próximo do vencimento')->line("O contrato {$this->contract->title} vence em {$this->daysRemaining} dias.");
    }

    public function toArray(object $notifiable): array
    {
        return ['notification_key' => "contract-expiring:{$this->contract->getKey()}:{$this->contract->ends_on?->toDateString()}", 'contract_id' => $this->contract->getKey(), 'title' => $this->contract->title, 'ends_on' => $this->contract->ends_on?->toDateString(), 'days_remaining' => $this->daysRemaining];
    }
}
