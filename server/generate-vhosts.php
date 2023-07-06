<?php

declare(strict_types=1);

new class () {
    private const NGINX_CONFIG = '/etc/nginx/conf.d/default.conf';
    private const INITAL_NGINX_CONF = 'inital_nginx.conf';
    private const INITAL_VM_PORT = 7000;
    private const THREE_MONTHS = 3 * 31 * 24 * 60 * 60;

    public function __construct()
    {
        $domainByVmNumber = [];

        $glob = new GlobIterator('/app/certs/*.crt');
        foreach ($glob as $file) {
            $domain = str_replace('.crt', '', $file->getFilename());

            if ($this->removeOldCert($file)) {
                continue;
            }

            $vmNumber = $this->getVmNumber($domain);

            $domainByVmNumber[$vmNumber][] = $domain;
        }

        $result = file_get_contents(self::INITAL_NGINX_CONF);
        $result = str_replace('213.61.68.122/32', $this->getEnv('DEFAULT_ALLOW_IP', '213.61.68.122/32'), $result);

        foreach ($domainByVmNumber as $vmNumber => $domains) {
            $result .= '

upstream vm' . $vmNumber . ' {
    server 127.0.0.1:' . (self::INITAL_VM_PORT + $vmNumber) . ';
}
';
            foreach ($domains as $domain) {
                $result .= 'server {
    server_name *.' . $domain . ';
    access_log /var/log/nginx/access.log vhost;
    listen 443 ssl http2;
    ssl_session_timeout 5m;
    ssl_session_cache shared:SSL:50m;
    ssl_session_tickets off;
    ssl_certificate /app/certs/' . $domain . '.crt;
    ssl_certificate_key /app/certs/' . $domain . '.key;
    location / {
        proxy_pass https://vm' . $vmNumber . ';
        set $upstream_keepalive false;
    }
}
';
            }
        }
        $content = file_get_contents(self::NGINX_CONFIG);
        if ($content === $result) {
            echo 'nothing changed' . PHP_EOL;
            exit(0);
        }
        file_put_contents(self::NGINX_CONFIG, $result);
        passthru('nginx -t', $resultCode);
        if ($resultCode) {
            exit($resultCode);
        }
        passthru('nginx -s reload', $resultCode);
        exit($resultCode);
    }

    private function getVmNumber(string $domain): int
    {
        preg_match($this->getEnv('VM_NUMBER_REGEX', '/(^|\.)vm(?<vmNumber>[0-9]{2})\./'), $domain, $matches);
        return (int)($matches['vmNumber'] ?? throw new \Exception(''));
    }

    private function removeOldCert(SplFileInfo $file): bool
    {
        if ($file->getMTime() < (time() - self::THREE_MONTHS)) {
            unlink($file->getPathname());
            $keyFile = str_replace('.crt', '.key', $file->getFilename());
            if (file_exists($keyFile)) {
                unlink($keyFile);
            }
            return true;
        }
        return false;
    }

    private function getEnv(string $key, string $fallback = null): string
    {
        $env = getenv($key) ?: $fallback;
        if (empty($env)) {
            throw new Exception(sprintf('ENVIRONMENT variable %s must be set.', $key));
        }

        return $env;
    }
};
