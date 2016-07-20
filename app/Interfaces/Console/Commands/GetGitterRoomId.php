<?php

/**
 * This file is part of GitterBot package.
 *
 * @author butschster <butschster@gmail.com>
 * @date 20.07.2016 14:44
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Interfaces\Console\Commands;

use Gitter\Client;
use Illuminate\Console\Command;

/**
 * Class StartGitterBot
 */
class GetGitterRoomId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gitter:get-room-id {room}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get gitter room id by name.';

    public function handle()
    {
        $client = new Client(\Config::get('gitter.token'));

        try {
            $result = $client->http->getRoomByUri($this->argument('room'))->wait();
            $this->info("Room ID: {$result->id}");
        } catch (\Exception $e) {
            $this->info('Room not found');
        }
    }
}