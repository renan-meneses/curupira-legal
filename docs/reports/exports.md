# Exportações

O primeiro relatório exportável é o CSV de recebíveis:

`GET /api/v1/reports/receivables.csv?month=YYYY-MM`

O arquivo contém cliente, documento, vencimento, valores em centavos, saldo e status. A consulta é filtrada pelo contexto da organização e usa `text/csv` com separador `;` para compatibilidade com planilhas brasileiras.

Também estão disponíveis:

- `GET /api/v1/reports/contracts.csv`
- `GET /api/v1/reports/time-entries.csv?month=YYYY-MM`
- `GET /api/v1/reports/billing.csv?month=YYYY-MM`
