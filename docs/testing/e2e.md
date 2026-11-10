# Testes E2E

Os testes Playwright ficam em `tests/E2E` e iniciam o servidor Laravel automaticamente. A cobertura inicial verifica health check e carregamento do shell visual; fluxos autenticados dependem de seed de usuário demo isolado para testes.

```bash
npx playwright install chromium
npm run e2e
```

Para preparar dados fictícios localmente:

```bash
php artisan migrate:fresh --seed --seeder=DemoSeeder
```

Credenciais padrão: `demo@curupira.local` / `CurupiraDemo-2026!`. Altere `DEMO_PASSWORD` fora de ambientes compartilhados. O seeder recusa execução em produção.

Artefatos de falha devem ser revisados antes de qualquer publicação e não podem conter dados reais.
