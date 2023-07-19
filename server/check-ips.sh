#!/bin/bash
set -e

mkdir -p /app/ip-allows/

function allowIps() {
  php generate-allow-ip.php
}

# sleep for 60 except if the certs change
while true; do
  allowIps
  inotifywait -r -e modify -e move -e create -e attrib -e delete --timeout 60 /app/certs
done
