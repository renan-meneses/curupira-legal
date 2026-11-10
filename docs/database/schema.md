# Esquema inicial

As primeiras migrations deverão usar ULIDs e timestamps. Entidades-base:

- `users`
- `organizations`
- `organization_memberships`
- `companies`
- `customers`
- `contracts`
- `audit_logs`

As tabelas de billing, fiscal e financeiro serão adicionadas somente com regras de fechamento e imutabilidade definidas. Índices compostos devem começar pelo `organization_id` nas tabelas tenant-scoped.
