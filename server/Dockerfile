FROM nginxproxy/forego:0.18.2-debian AS forego

FROM nginx:1.27.5-alpine

COPY --from=forego /usr/local/bin/forego /usr/local/bin/forego

RUN apk add openssh rsync bash nano inotify-tools php --no-cache

RUN echo "daemon off;" >> /etc/nginx/nginx.conf \
   && sed -i 's/worker_processes  1/worker_processes  auto/' /etc/nginx/nginx.conf \
   && sed -i 's/worker_connections  1024/worker_connections  10240/' /etc/nginx/nginx.conf \
   && mkdir -p '/etc/nginx/dhparam'

RUN sed -i "s/AllowTcpForwarding .*/AllowTcpForwarding yes/g" /etc/ssh/sshd_config \
   && echo 'ClientAliveInterval 15' >> /etc/ssh/sshd_config

WORKDIR /app

COPY ./* /app/

COPY inital_nginx.conf /etc/nginx/conf.d/default.conf

ENTRYPOINT ["sh", "/app/entrypoint.sh"]
CMD ["forego", "start", "-r"]

