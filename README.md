# vm-proxy for usage with docker-global

Allows access to VM sites for all collages

# the vm-proxy-client

the `andersundsehr/vm-proxy-client` creates a remote ssh tunnel to the `andersundsehr/vm-proxy-server`.  

````yaml
version: '3.5'
services:
  vm-proxy-client:
    image: andersundsehr/vm-proxy-client
    restart: unless-stopped
    volumes:
      - ~/.ssh:/home/user/.ssh
      - ./.docker/data/global-nginx-proxy/certs:/app/certs
    environment:
      - SSH_USER=root
      - SSH_HOST=ip of the vm-proxy-server
      - SSH_PORT=22
      - REMOTE_PORT=70${VM_NUMBER:?must be set}
      - DEST_HOST=global-nginx-proxy
      - DEST_PORT=443
````

# the vm-proxy-server

the `andersundsehr/vm-proxy-server` opens port `22`, `80` and `443`  
The Host there the vm-proxy-server is executed, needs a dedicated IP for the ports 80 and 443.

````yaml
version: '3.5'
services:
  vm-proxy-server:
    image: andersundsehr/vm-proxy-server
    restart: unless-stopped
    ports:
      - '0.0.0.0:22:22'
      - '0.0.0.0:80:80'
      - '0.0.0.0:443:443'
    volumes:
      - sshd:/etc/ssh
      - ssh:/root/.ssh
    environment:
      - INITAL_AUTHORIZED_KEYS=ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIOBmXh8Btp0eeuh6VbsbvvFpxoBdcOlhGR/7zcFMMgaF
      - DEFAULT_ALLOW_IP=213.61.68.122/32
      - VM_NUMBER_REGEX=/(^|\.)vm(?<vmNumber>[0-9]{2})\./

volumes:
  sshd:
  ssh:
````

with the `INITAL_AUTHORIZED_KEYS` env you can set and ssh key that is allowed to connect to the container.  
this key can add new keys to the container.


# with ♥️ from anders und sehr GmbH

> If something did not work 😮  
> or you appreciate this Extension 🥰 let us know.

> We are hiring https://www.andersundsehr.com/karriere/

