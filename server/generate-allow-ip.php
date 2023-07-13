<?php

declare(strict_types=1);

new class () {
    private const IP_TIMEOUT = 8 * 60 * 60;

    public function __construct()
    {
        $glob = new GlobIterator('/app/ip-configs/*.conf');
        $changed = false;
        foreach ($glob as $file) {
            if ($this->removeOldFiles($file)) {
                $changed = true;
            }
        }

        $ips = [];

        exec("netstat -tn | grep ESTABLISHED | grep ':22 ' | cut -d' ' -f20- | cut -d':' -f1 | uniq", $output);
        foreach ($output as $line) {
            $ips[] = trim($line);
        }
        $ips = array_unique($ips);
        foreach ($ips as $ip) {
            $fileName = '/app/ip-configs/' . $ip . '.conf';
            if (file_exists($fileName)) {
                touch($fileName);
            } else {
                file_put_contents($fileName, 'allow ' . $ip . '/32;');
            }
        }
        dd($ips);

        if ($changed) {
            passthru('nginx -t', $resultCode);
            if ($resultCode) {
                exit($resultCode);
            }
            passthru('nginx -s reload', $resultCode);
            exit($resultCode);
        }
    }

    private function removeOldFiles(SplFileInfo $file): bool
    {
        if ($file->getMTime() < (time() - self::IP_TIMEOUT)) {
            unlink($file->getPathname());
            return true;
        }
        return false;
    }
};

function dd()
{
    var_dump(...func_get_args());
    die();
}
