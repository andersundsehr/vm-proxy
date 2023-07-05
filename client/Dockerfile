FROM nginxproxy/forego:0.17.1-debian AS forego

FROM alpine:3.18

COPY --from=forego /usr/local/bin/forego /usr/local/bin/forego

RUN apk add openssh-client autossh rsync bash inotify-tools --no-cache

WORKDIR /app

COPY ./* /app/

ENTRYPOINT ["sh", "/app/entrypoint.sh"]
CMD ["forego", "start", "-r"]

