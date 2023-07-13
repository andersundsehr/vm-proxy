#!/bin/bash
set -e

# TODO allow all ip's with open Tunnels

mkdir -p /app/ip-configs/

function allowIps() {
  php generate-allow-ip.php
}

# sleep for 60 except if the certs change
while true; do
  allowIps
  inotifywait -r -e modify -e move -e create -e attrib -e delete --timeout 60 /app/certs
done
