# Progresso de desenvolvimento

## Fase 1 — fundação (em andamento)

- **Entregue:** identidade visual SVG, shell React/Vite responsivo, dashboard demonstrativo com tema claro/escuro, estados de aviso e menu móvel, `.env.example` e serviços PostgreSQL/Redis em Docker Compose.
- **Testes executados:** `git diff --check` passou no commit externo. `npm install --offline` falhou com `ENOTCACHED` para `@vitejs/plugin-react`; `npm run build` não foi executado por falta de dependências.
- **Documentação:** README, arquitetura inicial e diagramas serão mantidos junto da fundação.
- **Commit/push:** commit externo criado em `a9e928e572d6ebb650be8b36a655621893d31888` em 2026-11-10 14:30:50 -03:00. Push falhou porque `github.com` não resolve no ambiente (`Temporary failure in name resolution`). O diretório `.git` fornecido é um mount vazio e somente leitura; o commit está em `/tmp/curupira-legal-git` nesta sessão.
- **Limitação conhecida:** o scaffold Laravel não pôde ser baixado porque `repo.packagist.org` não resolve no ambiente. Não há backend ou autenticação real nesta etapa.

## Próximo marco

Restabelecer acesso ao Packagist e ao Git remoto; então criar Laravel 12, instalar Inertia/Fortify/Sanctum, migrar o shell React para `resources/js` e adicionar autenticação real com testes de isolamento de organização.

## Retomada — 10/11/2026

- **Entregue:** manifesto Composer Laravel 12, contexto de organização scoped, provider inicial e documentação de API, banco e regras de contratos.
- **Verificações:** `php -l` nos arquivos PHP passou; validação JSON de `composer.json` e `package.json` passou; `docker compose config` passou.
- **Bloqueios persistentes:** DNS indisponível para Packagist/GitHub; `docker compose up -d` foi impedido por falta de permissão ao socket Docker; `npm install --offline` continua sem metadata de `@vitejs/plugin-react`.

## Retomada — isolamento de organização

- **Entregue:** migrations ULID para organizações, usuários e memberships; modelos Eloquent; contexto scoped; middleware que rejeita organização ausente ou membership inválido; teste unitário Pest do contexto.
- **Verificações:** `php -l` passou para todos os arquivos PHP; validação do Composer, `git diff --check` e smoke test manual do `OrganizationContext` passaram.
- **Limitação:** migrations e teste ainda não foram executados pelo Laravel porque `vendor/` não existe.

## Retomada — autorização por papel

- **Entregue:** enum de papéis, modelo de membership, policy de organização e testes unitários para administração de membros e escrita financeira.
- **Verificações:** `php -l` passou em todos os arquivos PHP; smoke test do enum e `git diff --check` passaram.
- **Commit/push:** pendente; o mount `.git` permanece somente leitura.

## Fase 2 — empresas e clientes

- **Entregue:** migrations e modelos tenant-scoped para empresas e clientes; normalização/validação de CPF e CNPJ; unicidade de documento por organização; testes unitários do validador.
- **Verificações:** `php -l` passou em todos os arquivos PHP; smoke test de CPF/CNPJ e `git diff --check` passaram.
- **Limitação:** migrations e testes Pest ainda aguardam `vendor/` e PostgreSQL acessível.

## Fase 2 — endpoints de cadastro

- **Entregue:** Form Requests para empresa/cliente, regra reutilizável de CPF/CNPJ, controllers REST e rotas `/api/v1/company` e `/api/v1/customers` protegidas por Sanctum e contexto de organização.
- **Verificações:** `php -l` passou em todos os arquivos PHP; smoke test do pré-requisito de domínio e `git diff --check` passaram.
- **Limitação:** alias `organization.context`, Sanctum e serialização HTTP ainda precisam ser registrados/verificados pelo Laravel instalado.

## Correção — Vite e plugin React

- **Problema:** `@vitejs/plugin-react@6.1.1` tentava importar `vite/internal` com Vite 7.3.8.
- **Correção:** versões alinhadas para `@vitejs/plugin-react 6.1.1` e Vite 8.x.
- **Verificação:** `npm ls vite @vitejs/plugin-react` passou com Vite 8.3.7; `npm run build` passou, gerando `dist/` em 101 ms.

## Fase 3 — contratos

- **Entregue:** enums de estado e modelo de cobrança, migration tenant-scoped, modelo Eloquent, request de criação e endpoints REST de listagem/criação.
- **Segurança:** o controller confirma que o cliente pertence à organização ativa antes de criar o contrato; `organization_id` não é aceito do payload.
- **Verificações:** todos os arquivos PHP passaram em `php -l`, smoke test dos enums passou, `git diff --check` passou e `npm run build` passou.
- **Limitação:** transições de estado, versionamento, anexos e execução via Laravel ainda aguardam o scaffold/vendor completo.

## Fase 3 — versionamento e ciclo de vida

- **Entregue:** tabela de versões imutáveis, snapshot de transição, ciclo de vida explícito e endpoint `PATCH /api/v1/contracts/{contract}/status` com lock transacional e escopo de organização.
- **Verificações:** `php -l` passou, smoke test do ciclo de vida passou e `git diff --check` passou.
- **Limitação:** a transação e o model binding ainda não foram exercitados pelo runtime Laravel sem `vendor/` e banco PostgreSQL.

## Fase 4 — time tracking

- **Entregue:** apontamentos em minutos inteiros, vínculo tenant-scoped a contratos, estados draft/submitted/approved/rejected, aprovação com usuário e data, rejeição com justificativa e endpoints REST.
- **Rotas:** `GET/POST /api/v1/time-entries` e `PATCH /api/v1/time-entries/{timeEntry}/review`.
- **Verificações:** `php -l` passou em todos os arquivos PHP, smoke test do ciclo de vida passou e `git diff --check` passou.
- **Limitação:** timer em tempo real, regras de horas extras e execução de migrations ainda aguardam o runtime Laravel/PostgreSQL.

## Fase 5 — billing horário

- **Entregue:** `Money` com centavos inteiros, `BillingEngine` baseado em apontamentos aprovados, períodos, registros idempotentes e snapshots de cálculo.
- **Verificações:** `php -l` passou nos arquivos PHP; smoke test de aritmética exata e `git diff --check` passaram.
- **Limitação:** endpoint de geração, fechamento de período e modelos fixo/híbrido ainda serão adicionados após validar o runtime Laravel.

## Fase 5 — geração e fechamento

- **Entregue:** endpoints `POST /api/v1/billing/hourly` e `POST /api/v1/billing/periods/{billingPeriod}/finalize`; períodos abertos/fechados; finalização transacional de registros; bloqueio de nova geração em período fechado.
- **Verificações:** `php -l` passou, smoke test do ciclo de vida do período passou e `git diff --check` passou.
- **Limitação:** autenticação, middleware alias e banco ainda não foram executados pelo runtime Laravel.

## Fase 8 — financeiro inicial

- **Entregue:** recebíveis derivados de billing finalizado, pagamentos parciais, métodos de pagamento, saldo em centavos e lock transacional contra pagamento acima do saldo.
- **Rotas:** `GET/POST /api/v1/receivables` e `POST /api/v1/receivables/{receivable}/payments`.
- **Verificações:** `php -l` passou, smoke test dos enums financeiros passou e `git diff --check` passou.
- **Limitação:** relatórios, conciliação, cobrança automática e execução no banco ainda aguardam o runtime Laravel/PostgreSQL.

## Fase 1 — onboarding de organizações

- **Entregue:** criação transacional de organização, membership administrativo inicial, listagem de organizações do usuário e troca de organização com verificação de associação.
- **Rotas:** `GET/POST /api/v1/organizations` e `POST /api/v1/organizations/{organization}/switch`.
- **Verificações:** `php -l` passou em todos os arquivos PHP, smoke test de papel administrativo e `git diff --check` passaram.
- **Limitação:** Sanctum, Fortify e o alias `organization.context` ainda aguardam registro no scaffold Laravel executável.

## Fase 1 — autenticação Fortify

- **Entregue:** ação de criação de usuário com senha mínima de 12 caracteres, e-mail normalizado, configuração Fortify para registro, recuperação, verificação, atualização de senha e 2FA, além de rate limits para login e 2FA.
- **Verificações:** validação estática dos arquivos PHP pendente de execução abaixo.
- **Limitação:** rotas Fortify, sessão, e-mail e 2FA ainda não foram exercitados sem o runtime Laravel/vendor.

## Fase 1 — hardening de identidade

- **Entregue:** usuário implementa verificação de e-mail, criação de conta separada do onboarding de tenant, senha mínima de 12 caracteres e rate limits independentes para login/2FA.
- **Verificações:** `php -l` passou em todos os arquivos PHP, validação de configuração e `git diff --check` passaram.
- **Próxima integração:** registrar `FortifyServiceProvider`, `OrganizationServiceProvider` e o alias `organization.context` em `bootstrap/app.php` após disponibilizar o scaffold Laravel executável.

## Fase 1 — bootstrap Laravel

- **Entregue:** `bootstrap/app.php`, registro de providers, alias `organization.context`, rotas web/API/console e view Blade de entrada para o shell React.
- **Verificações:** todos os arquivos PHP passaram em `php -l`, `git diff --check` passou e `npm run build` passou com Vite 8.3.7.
- **Limitação:** a view Blade depende do scaffold Laravel e do `laravel-vite-plugin`, ainda não instalados neste ambiente.

## Fase 1 — runtime Laravel verificável

- **Entregue:** scaffold mínimo (`artisan`, `public/index.php`, configurações base, diretórios de storage/cache e `Controller` base) e configuração PHPUnit/Pest.
- **Verificações:** `composer install` passou; `composer dump-autoload` passou; `php artisan about` passou; `php artisan config:cache` passou; `php artisan route:list` encontrou 64 rotas; `php artisan test` passou com 15 testes e 26 assertions.
- **Pendente:** migrations reais ainda dependem de PostgreSQL acessível; o socket Docker permanece sem permissão neste ambiente.

## Fase 1 — persistência e isolamento verificáveis

- **Entregue:** migrations executadas com sucesso em SQLite temporário; traits `HasUlids`; harness de testes Laravel; primeiro Feature Test de isolamento entre organizações; contexto API via `X-Organization-Id` com fallback de sessão web.
- **Verificações:** `DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test` passou com 16 testes e 29 assertions; migration fresh SQLite passou com as 12 migrations.
- **Limitação:** a validação final contra PostgreSQL ainda depende de um servidor PostgreSQL acessível.

## Fase 6 — base tributária configurável

- **Entregue:** migrations para configurações fiscais efetivadas por data e snapshots; `TaxEngine` com basis points inteiros; separação entre retido, fora da nota e estimado.
- **Regra de segurança:** o motor calcula somente regras fornecidas pela configuração aplicável; configuração vazia não inventa alíquotas.
- **Verificações:** migrations SQLite até `000014` passaram; testes do motor passaram com 2 testes e 10 assertions; todos os arquivos PHP passaram em `php -l`.
- **Limitação:** cadastro/revisão de configuração, preview HTTP e integração de legislação ainda serão adicionados.

## Fase 6 — configuração, revisão e preview fiscal

- **Entregue:** resolver de configuração ativa por serviço, municípios, regime e vigência; cadastro em rascunho; revisão por administrador/contador; preview HTTP e snapshot persistido.
- **Rotas:** `GET/POST /api/v1/tax/configurations`, `PATCH /api/v1/tax/configurations/{taxConfiguration}/review` e `POST /api/v1/tax/preview`.
- **Verificações:** Feature Test fiscal passou com 3 testes/15 assertions no recorte; suíte completa passou; migrations SQLite até `000014` passaram.
- **Limitação:** regras oficiais devem ser revisadas por contador e integração NFS-e ainda não foi conectada.

## Fase 7 — abstração NFS-e

- **Entregue:** `FiscalProviderInterface`, DTOs de submissão/status, `MockFiscalProvider`, estados de invoice, tabela de invoices, preparação DPS e submissão que permanece `processing` até reconciliação.
- **Rotas:** `POST /api/v1/fiscal/invoices/prepare` e `POST /api/v1/fiscal/invoices/{fiscalInvoice}/submit`.
- **Verificações:** todos os arquivos PHP passaram em `php -l`; rotas fiscais aparecem no `route:list`; testes do mock passaram.
- **Limitação:** nenhum provedor governamental real foi conectado; integração oficial exige credenciais, autorização e documentação do emissor.

## Fase 7 — reconciliação fiscal

- **Entregue:** tabela de eventos fiscais, consulta de status por protocolo e reconciliação transacional da invoice.
- **Segurança:** cada retorno é auditado; o status `authorized` só é aplicado se vier do provedor, e o mock permanece em `processing`.
- **Rota:** `POST /api/v1/fiscal/invoices/{fiscalInvoice}/reconcile`.
- **Verificações:** 2 testes fiscais passaram com 4 assertions; todos os arquivos PHP passaram em `php -l`; `git diff --check` passou.

## Fase 13 — auditoria e compliance

- **Entregue:** `audit_logs`, `AuditLogger` e registro de pagamentos, revisão fiscal, submissão e reconciliação NFS-e.
- **Propriedade:** eventos são append-only na aplicação e incluem organização, usuário, ação, recurso, metadata e timestamp.
- **Verificações:** 24 testes compactos passaram; `php -l` passou em todos os arquivos; migrations SQLite até `000017` passaram; `git diff --check` passou.

## Fase 14 — automações

- **Entregue:** jobs idempotentes para reconciliação fiscal e vencimento de recebíveis, com Scheduler a cada 5 minutos/diário e proteção contra sobreposição.
- **Documentação:** [scheduler.md](deployment/scheduler.md).
- **Verificação:** sintaxe PHP e descoberta de rotas/commands serão validadas junto da próxima execução completa do Artisan.

## Fase 11 — notificações

- **Entregue:** notificações persistidas e por e-mail para contratos próximos do vencimento e invoices rejeitadas, com deduplicação por `notification_key`.
- **Agendamento:** contratos às 08:00; rejeições fiscais a cada 10 minutos.
- **Limitação:** certificados digitais ainda não possuem módulo de metadata para alertas de expiração.

## Fase 10 — dashboard

- **Entregue:** endpoint `GET /api/v1/dashboard` com competência mensal, contratos ativos/próximos do vencimento, minutos aprovados, billing finalizado, recebíveis em aberto, invoices e impostos retidos.
- **Segurança:** todos os agregados usam o `organization_id` do contexto ativo.
- **Verificação:** Feature Test de dashboard passou com 3 assertions; `php -l` e `git diff --check` passaram.

## Fase 9 — relatórios CSV

- **Entregue:** exportação CSV mensal de recebíveis, com cliente, documento, vencimento, valor, pago, saldo e status.
- **Segurança:** relatório usa contexto obrigatório da organização ativa.
- **Verificação:** Feature Test de exportação passou com 3 assertions; `php -l` e `git diff --check` passaram.

## Fase 9 — relatórios adicionais

- **Entregue:** exportações CSV de contratos, horas trabalhadas e billing mensal.
- **Verificação:** exportação de contratos adicionada ao Feature Test; sintaxe PHP e `git diff --check` verificados.

## Fase 10 — documentação OpenAPI

- **Entregue:** especificação OpenAPI 3.1 para os endpoints principais, autenticação Sanctum e header de organização ativa.
- **Verificação:** arquivo YAML revisado junto das rotas atuais; validação automática com ferramenta externa fica pendente no CI.

## Fase 17 — CI/CD

- **Entregue:** workflow GitHub Actions para backend, frontend, auditoria de dependências e OpenAPI.
- **Política:** execução em `main` e pull requests; sem deploy automático.
- **Limitação:** execução remota depende de acesso ao GitHub e ainda não foi observada neste ambiente.

## Fase 2/3 — Feature Tests de autorização

- **Entregue:** testes HTTP para criação de cliente no tenant ativo, isolamento de listagem e bloqueio de contrato ligado a cliente de outra organização.
- **Verificação:** `DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test` passou com 18 testes e 34 assertions.

## Fase 4/5/8 — workflow financeiro integrado

- **Entregue:** submissão de apontamento, aprovação, geração de billing horário, fechamento do período, criação de recebível e pagamento parcial em um fluxo HTTP único.
- **Correção aplicada:** período de billing agora recebe `status=open` explicitamente na criação, evitando enum vazio antes do default do banco.
- **Verificação:** workflow passou com 11 assertions; suíte completa passou com 19 testes e 45 assertions usando SQLite em memória.
