#!/bin/bash
set -e

if [ ! -f /etc/ssh/ssh_host_rsa_key ]; then
  ssh-keygen -A
fi

if [ ! -f /root/.ssh/authorized_keys ]; then
  echo $INITAL_AUTHORIZED_KEYS > /root/.ssh/authorized_keys
fi

exec "$@"
