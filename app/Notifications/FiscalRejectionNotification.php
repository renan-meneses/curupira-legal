<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\FiscalInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class FiscalRejectionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly FiscalInvoice $invoice) {}
    public function via(object $notifiable): array { return ['database', 'mail']; }
    public function toMail(object $notifiable): MailMessage { return (new MailMessage())->subject('Rejeição fiscal')->line('Uma NFS-e foi rejeitada pelo provedor fiscal.')->line('Protocolo: '.$this->invoice->protocol); }
    public function toArray(object $notifiable): array { return ['notification_key' => "fiscal-rejected:{$this->invoice->getKey()}", 'fiscal_invoice_id' => $this->invoice->getKey(), 'protocol' => $this->invoice->protocol, 'reason' => $this->invoice->rejection_reason]; }
}
