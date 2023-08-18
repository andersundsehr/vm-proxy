#!/bin/bash

cp -r /home/user/.ssh/ /root/.ssh/
chown -R root:root /root/.ssh
chmod 700 /root/.ssh
chmod 600 /root/.ssh/authorized_keys
chmod 400 /root/.ssh/id_rsa
chmod 644 /root/.ssh/id_rsa.pub
chmod 600 /root/.ssh/known_hosts

exec "$@"
