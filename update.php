<?php

declare(strict_types=1);

/**
 * DynDnsService
 * Dieses Skript ist für PHP 8.4
 */
class DynDnsService
{
    private const API_URL = 'https://www.febas.de/api/dyndns.php';

    public function run(): void
    {
        $auth = $this->getAuth();
        $host = (string) ($_GET['hostname'] ?? '');

        if (empty($auth['user']) || empty($auth['pass'])) {
            http_response_code(401);
            exit('badauth');
        }

        if ($host === '') {
            exit('notfqdn');
        }

        $ips = $this->getIPs();
        $response = $this->sendUpdate($auth, $host, $ips);

        echo $this->parseResponse($response);
    }

    private function getAuth(): array
    {
        return [
            'user' =>
                (string) ($_SERVER['PHP_AUTH_USER'] ?? ($_GET['user'] ?? '')),
            'pass' =>
                (string) ($_SERVER['PHP_AUTH_PW'] ?? ($_GET['pass'] ?? '')),
        ];
    }

    private function getIPs(): array
    {
        $raw = (string) ($_GET['myip'] ?? '');
        $ips = explode(',', $raw);

        $v4 = '';
        $v6 = '';

        foreach ($ips as $ip) {
            $ip = trim($ip);
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $v4 = $ip;
            }
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $v6 = $ip;
            }
        }

        return ['v4' => $v4, 'v6' => $v6];
    }

    private function sendUpdate(
        array $auth,
        string $host,
        array $ips
    ): string|bool {
        $query = http_build_query([
            'kundenid' => $auth['user'],
            'token' => $auth['pass'],
            'type' => 'dyndns',
            'domain' => $host,
            'myip' => $ips['v4'],
            'myip6' => $ips['v6'],
        ]);

        $ch = curl_init(self::API_URL . "?{$query}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }

    private function parseResponse(mixed $res): string
    {
        if ($res === false) {
            return 'dnserr';
        }

        $resStr = strtolower(trim((string) $res));

        return match (true) {
            str_contains($resStr, 'good') => 'good',
            str_contains($resStr, 'badauth') => 'badauth',
            default => 'dnserr',
        };
    }
}

$service = new DynDnsService();
$service->run();
