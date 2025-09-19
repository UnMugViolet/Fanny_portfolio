#!/bin/bash
set -e

host="$1"
port="$2"
shift 2
cmd="$@"

echo "Waiting for database at $host:$port..."

until mysql -h"$host" -P"$port" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e 'SELECT 1' >/dev/null 2>&1; do
  echo "Database is unavailable - sleeping"
  sleep 2
done

echo "Database is up - executing command"
exec $cmd
