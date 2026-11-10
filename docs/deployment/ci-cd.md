# CI/CD

O workflow `.github/workflows/ci.yml` roda em pushes para `main` e pull requests:

- valida Composer e dependências PHP;
- executa migrations SQLite e suíte Laravel;
- executa `composer audit`;
- valida TypeScript e build Vite;
- executa `npm audit`;
- valida `docs/api/openapi.yaml`.

O pipeline não faz deploy automático. Credenciais de produção e integrações fiscais devem ser configuradas em secrets quando houver uma estratégia de deploy aprovada.
