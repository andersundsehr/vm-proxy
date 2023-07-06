#!/bin/bash
set -e

# TODO allow all ip's with open Tunnels

# maybe use netstat -t

function allowIps() {
  ALLOWED_IPS=$(lsof -i -n | egrep '\<ssh\>' |grep '-' | cut -d'>' -f2 | cut -d: -f1 | uniq)
  echo "$ALLOWED_IPS"

  ALLOWED_IPS=$(netstat -t)
  echo "$ALLOWED_IPS"

  tail -f /dev/null
}

allowIps
while sleep 1; do
  allowIps
done
