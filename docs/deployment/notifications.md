# Notificações

Jobs agendados:

- `NotifyExpiringContracts`: diariamente às 08:00 para contratos ativos que vencem em até 30 dias.
- `NotifyRejectedFiscalInvoices`: a cada 10 minutos para rejeições fiscais.

Cada notificação inclui `notification_key` e é consultada antes do envio para evitar duplicatas por organização e recurso. O canal database mantém o histórico; o canal mail depende da configuração SMTP/serviço de e-mail.
