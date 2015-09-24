<?php

namespace App\Console\Commands;

use App\Gitter\Client;
use App\Gitter\Models\Message;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;

/**
 * Class StartGitterBot
 * @package App\Console\Commands
 */
class StartGitterBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gitter:listen';

    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Startup gitter bot';


    /**
     * Execute the console command.
     *
     * @param Repository $config
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function handle(Repository $config)
    {
        $token = $config->get('gitter.token');
        $rooms = $config->get('gitter.rooms');


        $client = new Client($token);
        $client
            ->stream('messages', ['roomId' => $rooms[0]])
            ->subscribe(function ($data) {

                var_dump(new Message($data));

            });

        $client->run();
    }
}
