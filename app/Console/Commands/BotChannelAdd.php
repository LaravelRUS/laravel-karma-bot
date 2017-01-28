<?php declare(strict_types=1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Console\Commands;

use App\Model\System;
use App\Model\Channel;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\Container;

/**
 * Class BotChannelAdd
 * @package App\Console\Commands
 */
class BotChannelAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:channel 
        {systemId : System id} 
        {channel : Channel external id}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update or add channel into system';

    /**
     * @param Container $app
     * @return void
     * @throws \InvalidArgumentException
     */
    public function handle(Container $app): void
    {
        $system = System::find($this->argument('systemId'));
        if (!$system) {
            throw new \InvalidArgumentException('Invalid system id');
        }

        $channel = $this->getChannel($system);

        $data = $channel->getChannelConnection($app);
        $channel->name = $data->getName();
        $channel->save();
    }

    /**
     * @param System $system
     * @return Channel
     */
    private function getChannel(System $system): Channel
    {
        $channelId = $this->argument('channel');
        $channel = Channel::inSystem($system)->withExternalId($channelId)->first();

        if (!$channel) {
            $channel = new Channel();
            $channel->system_id = $system->id;
            $channel->sys_channel_id = $channelId;
        }

        return $channel;
    }
}
