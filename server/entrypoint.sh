#!/bin/bash
set -e

if [ ! -f /etc/ssh/ssh_host_rsa_key ]; then
  ssh-keygen -A
fi

sed "s/AllowTcpForwarding .*/AllowTcpForwarding yes/g" /etc/ssh/sshd_config > /etc/ssh/sshd_config

if [ ! -f /root/.ssh/authorized_keys ]; then
  echo $INITAL_AUTHORIZED_KEYS > /root/.ssh/authorized_keys
fi

exec "$@"
