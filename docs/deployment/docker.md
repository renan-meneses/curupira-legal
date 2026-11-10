# Docker local

O `docker-compose.yml` atual sobe PostgreSQL 16 e Redis 7 com volumes persistentes e health checks. O serviço Laravel/Nginx será adicionado junto ao scaffold Laravel, pois ainda não foi possível baixar as dependências do Composer.

```bash
docker compose up -d postgres redis
docker compose ps
```
