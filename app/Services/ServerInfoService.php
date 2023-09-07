<?php

namespace App\Services;

use App\Events\ServerDown;
use App\Events\ServerUp;
use Exception;
use phpseclib3\Crypt\RSA;
use phpseclib3\Net\SSH2;

class ServerInfoService
{
    private SSH2 $ssh;

    /**
     * @throws Exception
     */
    public function __construct(
        private readonly string $sshKey,
        private readonly object $serverData,
    ) {
        $rsa = RSA::loadPrivateKey($this->sshKey);

        $this->ssh = new SSH2($this->serverData->hostname, $this->serverData->port);
        if (!$this->ssh->login($this->serverData->user, $rsa)) {
            event(new ServerDown($this->serverData->hostname));
            throw new Exception('Failed to login to SSH');
        }
        event(new ServerUp($this->serverData->hostname));
    }

    public function getServerInfo(): array
    {
        $services_to_check = [];
        $connections_check = [];

        if (isset($this->serverData->services_to_check)) {
            foreach ($this->serverData->services_to_check as $serviceToCheck) {
                $services_to_check[$serviceToCheck] = $this->isRunning($serviceToCheck);
            }
        }

        if (isset($this->serverData->connections_check)) {
            foreach ($this->serverData->connections_check as $connectionCheck) {
                $connections_check[$connectionCheck] = $this->getConnections($connectionCheck);
            }
        }

       return [
           'RAM_usage' => $this->getRamUsage(),
           'RAM_max' => $this->getMaxRam(),
           'CPU_usage' => $this->getCpuUsage(),
           'load_avg' => $this->getLoadAverage(),
           'disk_capacity' => $this->getDiskCapacity(),
           'services_check' => $services_to_check,
           'connections_check' => $connections_check,
        ];
    }

    protected function getRamUsage(): int
    {
        $output = $this->ssh->exec('cat /proc/meminfo | grep "MemTotal"');
        preg_match('/(\d+)/', $output, $matches);
        $totalMemory = $matches[1];

        $output = $this->ssh->exec('cat /proc/meminfo | grep "MemAvailable"');
        preg_match('/(\d+)/', $output, $matches);
        $availableMemory = $matches[1];

        $ramUsage = $totalMemory - $availableMemory;
        return intval($ramUsage / 1000);
    }

    protected function getMaxRam(): int
    {
        $output = $this->ssh->exec('cat /proc/meminfo | grep "MemTotal"');
        preg_match('/(\d+)/', $output, $matches);
        $totalMemory = $matches[1];
        return intval($totalMemory / 1000);
    }

    private function getCpuUsage(): float
    {
        $output = $this->ssh->exec('mpstat | awk \'NR > 3 { sum += 100 - $NF } END { if (NR > 3) print sum / (NR - 3); else print 0 }\'');
        return floatval(str_replace(',','.',$output));
    }

    private function getLoadAverage(): array
    {
        $output = $this->ssh->exec('uptime | awk -F "load average: " \'{print $2}\'');
        $loadInfo = explode(', ', $output);

        return [
            '1m' => (float) str_replace(',','.',$loadInfo[0]),
            '5m' => (float) str_replace(',','.',$loadInfo[1]),
            '15m' => (float) str_replace(',','.',$loadInfo[2]),
        ];
    }

    private function getDiskCapacity(): array
    {
        $output = $this->ssh->exec('df -m / | tail -n 1'); // Zmieniamy -h na -m, aby uzyskać wyniki w megabajtach
        $diskInfo = preg_split('/\s+/', $output);

        return [
            'total_mb' => intval($diskInfo[1]),
            'free_mb' => intval($diskInfo[3]),
            'free_in_percent' => intval(str_replace('%', '', $diskInfo[4]))
        ];
    }

    private function getConnections(string $port): int
    {
        $output = $this->ssh->exec("netstat -an | grep {$port} | wc -l");
        return trim($output);
    }

    protected function isRunning(string $service): bool
    {
        $output = $this->ssh->exec("ps aux | grep {$service} | grep -v grep");

        return !empty($output);
    }


    public function __destruct()
    {
        $this->ssh->disconnect();
    }

}
