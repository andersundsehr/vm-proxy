#!/bin/bash
set -e

function syncFiles() {
  rsync -rtuv -e "ssh -p $SSH_PORT" --exclude="default.*" /app/certs/ $SSH_USER@$SSH_HOST:/app/certs/
}

while true; do
  syncFiles
  inotifywait -r -e modify -e move -e create -e attrib -e delete --timeout 3600 /app/certs
done
