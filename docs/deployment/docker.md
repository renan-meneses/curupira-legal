# Docker local

O `docker-compose.yml` sobe PHP-FPM/Laravel, Nginx, worker, Horizon, Scheduler, PostgreSQL 16 e Redis 7 com volumes persistentes e health checks.

```bash
docker compose up -d postgres redis
docker compose ps
```

Para subir a aplicação completa:

```bash
cp .env.example .env
docker compose build
docker compose up -d
docker compose exec app php artisan migrate --force
```

A aplicação fica em `http://localhost:8080`. O worker e o Horizon usam Redis; o Scheduler executa os jobs registrados em `routes/console.php`.
