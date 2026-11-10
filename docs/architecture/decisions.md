# Decisões arquiteturais iniciais

## ADR-001 — monólito modular

O produto começa como um monólito modular Laravel para manter transações financeiras e regras fiscais próximas, reduzir operação e permitir extração futura por domínio quando houver evidência de necessidade.

## ADR-002 — PostgreSQL como fonte transacional

Dados de contratos, faturamento, impostos e recebíveis terão constraints e transações no PostgreSQL. Redis será usado para sessões, filas, cache e locks idempotentes, nunca como fonte financeira.

## ADR-003 — Inertia + React

Inertia permite navegação de aplicação rica sem criar uma API pública duplicada para cada tela. Endpoints REST documentados serão adicionados para integrações externas e automações.

## ADR-004 — provedor fiscal por contrato

NFS-e será isolada atrás de `FiscalProviderInterface`; o mock cobrirá testes e o provedor nacional/municipal só poderá marcar autorização após confirmação do serviço oficial.
