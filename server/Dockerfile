FROM nginxproxy/forego:0.17.1-debian AS forego

FROM nginx:1.25.0-alpine

COPY --from=forego /usr/local/bin/forego /usr/local/bin/forego

RUN apk add openssh rsync bash nano inotify-tools php --no-cache

RUN echo "daemon off;" >> /etc/nginx/nginx.conf \
   && sed -i 's/worker_processes  1/worker_processes  auto/' /etc/nginx/nginx.conf \
   && sed -i 's/worker_connections  1024/worker_connections  10240/' /etc/nginx/nginx.conf \
   && mkdir -p '/etc/nginx/dhparam'

WORKDIR /app

COPY ./* /app/

COPY inital_nginx.conf /etc/nginx/conf.d/default.conf

ENTRYPOINT ["sh", "/app/entrypoint.sh"]
CMD ["forego", "start", "-r"]

