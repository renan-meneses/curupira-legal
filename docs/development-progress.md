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
