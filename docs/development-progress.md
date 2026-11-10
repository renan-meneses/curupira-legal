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
