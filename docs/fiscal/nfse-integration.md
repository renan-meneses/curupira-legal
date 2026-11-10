# Integração NFS-e

O domínio fiscal usa `FiscalProviderInterface`. O `MockFiscalProvider` é apenas para desenvolvimento e testes: a submissão retorna `processing`, a consulta permanece `processing` e o mock nunca confirma autorização.

Rotas atuais:

- `POST /api/v1/fiscal/invoices/prepare`
- `POST /api/v1/fiscal/invoices/{fiscalInvoice}/submit`
- `POST /api/v1/fiscal/invoices/{fiscalInvoice}/reconcile`

Uma nota só poderá ser marcada como `authorized` após reconciliação de resposta de um provedor oficial autorizado. Este repositório não coleta senha gov.br, não automatiza CAPTCHA/MFA e não inventa respostas governamentais.

Submissões e reconciliações geram eventos de auditoria com protocolo e status retornado.
