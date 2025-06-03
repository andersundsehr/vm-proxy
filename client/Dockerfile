FROM nginxproxy/forego:0.18.3-debian AS forego

FROM alpine:3.22

COPY --from=forego /usr/local/bin/forego /usr/local/bin/forego

RUN apk add openssh-client autossh rsync bash inotify-tools --no-cache

WORKDIR /app

COPY ./* /app/

ENTRYPOINT ["sh", "/app/entrypoint.sh"]
#CMD ["forego", "start", "-r"]
# do not restart for now:
CMD ["forego", "start"]

