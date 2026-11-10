# Scheduler e filas

Jobs configurados:

- `ReconcilePendingFiscalInvoices`: a cada 5 minutos, reconcilia invoices `processing` em lotes de 100.
- `MarkOverdueReceivables`: diariamente às 00:15, marca recebíveis abertos ou parciais cujo vencimento passou.

Em produção, mantenha um worker Horizon ativo e execute o scheduler do Laravel a cada minuto:

```bash
php artisan horizon
php artisan schedule:work
```

Ambos os jobs têm tentativas limitadas e são seguros para repetição; o lock `withoutOverlapping` evita execuções simultâneas do agendamento.
