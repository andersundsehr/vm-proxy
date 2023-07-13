#!/bin/bash
set -e

mkdir -p /app/certs

function createVhosts() {
  php /app/generate-vhosts.php
}

while true; do
  createVhosts
  inotifywait -r -e modify -e move -e create -e attrib -e delete --timeout 3600 /app/certs
done
