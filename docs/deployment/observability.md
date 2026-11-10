# Observabilidade

## Health check

`GET /healthz` verifica a aplicação, banco e cache. Responde `200` com `status=ok` quando tudo está disponível e `503` com `status=degraded` quando uma dependência falha. A resposta não inclui mensagens internas.

## Correlação

Todas as requisições recebem `X-Request-Id`. Se o cliente enviar um ID válido, ele é preservado; caso contrário, o aplicativo gera um UUID. Use esse valor para correlacionar logs, auditoria e chamados de suporte.

Logs de produção devem ser enviados para um agregador estruturado com retenção compatível com LGPD e política de auditoria.
