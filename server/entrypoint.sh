#!/bin/bash
set -e

ssh-keygen -A
sed "s/AllowTcpForwarding .*/AllowTcpForwarding yes/g" /etc/ssh/sshd_config > /etc/ssh/sshd_config


if [ ! -f /root/.ssh/authorized_keys ]; then
  echo $INITAL_AUTHORIZED_KEYS > /root/.ssh/authorized_keys
fi

exec "$@"
