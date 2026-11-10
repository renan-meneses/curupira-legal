# Recebíveis e pagamentos

Billing finalizado e recebível são estados separados. Um recebível nasce uma vez a partir de um `billing_record` finalizado e guarda valor original e saldo pago em centavos inteiros.

Pagamentos podem ser parciais. O sistema rejeita valores acima do saldo e altera o status para `partial` ou `paid`. A operação usa lock transacional para evitar dupla baixa concorrente.

Rotas:

- `GET /api/v1/receivables`
- `POST /api/v1/receivables`
- `POST /api/v1/receivables/{receivable}/payments`
