# Produção

## Requisitos mínimos

- HTTPS terminado no proxy/load balancer.
- `APP_ENV=production` e `APP_DEBUG=false`.
- `APP_KEY` gerada fora do Git e armazenada em secret manager.
- PostgreSQL com backup automatizado e teste periódico de restauração.
- Redis privado, sem exposição pública.
- S3 compatível com bucket privado e criptografia habilitada.
- Worker Horizon e Scheduler monitorados.

## Backup PostgreSQL

O script `docker/backup/backup-postgres.sh` gera dump sem owner/privilégios, comprime e envia para `BACKUP_S3_URI`. Ele exige `pg_dump` e AWS CLI no ambiente executor.

```bash
PGHOST=postgres \
PGPORT=5432 \
PGDATABASE=curupira \
PGUSER=curupira \
BACKUP_S3_URI=s3://curupira-backups/production \
sh docker/backup/backup-postgres.sh
```

Configure retenção no bucket e teste restauração em ambiente isolado. O script não apaga backups antigos; a retenção deve ser aplicada por lifecycle policy do S3.

## Deploy

```bash
docker compose build
docker compose up -d
docker compose exec app php artisan migrate --force
docker compose exec app php artisan storage:link
```

Não execute deploy automático a partir deste repositório até configurar secrets, migrações aprovadas, observabilidade e rollback.
