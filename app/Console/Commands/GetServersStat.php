<?php

namespace App\Console\Commands;

use App\Models\ServerHistory;
use App\Services\ServerInfoService;
use Exception;
use Illuminate\Console\Command;

class GetServersStat extends Command
{
    protected $signature = 'get-servers-stat {hostname}';
    protected $description = 'Get servers stat';

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $servers = config('services.servers');
        $hostname = $this->argument('hostname');
        $sshKey = base64_decode(config('services.id_rsa_key_base64'));


        foreach ($servers as $server) {
            if (isset($server->hostname) && $server->hostname == $hostname) {
                $serverInfoService = new ServerInfoService(
                    $sshKey,
                    $server
                );

                while (true) {
                    $output = $serverInfoService->getServerInfo();
                    $output['hostname'] = $hostname;
                    ServerHistory::create($output);
                    sleep(5);
                }
            }
        }

        throw new Exception("I cant find this server in configuration.");



    }
}
