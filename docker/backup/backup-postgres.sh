#!/usr/bin/env sh
set -eu

: "${PGHOST:?PGHOST é obrigatório}"
: "${PGPORT:?PGPORT é obrigatório}"
: "${PGDATABASE:?PGDATABASE é obrigatório}"
: "${PGUSER:?PGUSER é obrigatório}"
: "${BACKUP_S3_URI:?BACKUP_S3_URI é obrigatório}"

timestamp="$(date -u +%Y%m%dT%H%M%SZ)"
file="/tmp/curupira-${PGDATABASE}-${timestamp}.sql.gz"

cleanup() { rm -f "$file"; }
trap cleanup EXIT INT TERM

pg_dump --no-owner --no-privileges --format=plain | gzip -9 > "$file"
aws s3 cp "$file" "${BACKUP_S3_URI%/}/${PGDATABASE}/${timestamp}.sql.gz" --only-show-errors

echo "Backup enviado para ${BACKUP_S3_URI%/}/${PGDATABASE}/${timestamp}.sql.gz"
