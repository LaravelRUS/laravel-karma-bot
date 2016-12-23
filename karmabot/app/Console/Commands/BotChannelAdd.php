<?php declare(strict_types=1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Console\Commands;

use Illuminate\Console\Command;
use KarmaBot\Model\Channel;
use KarmaBot\Model\System;
use Serafim\KarmaCore\Io\ChannelInterface;
use Serafim\KarmaCore\Io\ManagerInterface;
use Serafim\KarmaCore\Io\SystemInterface;

/**
 * Class BotChannelAdd
 * @package KarmaBot\Console\Commands
 */
class BotChannelAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:channel {system} {channel}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update or add channel into system';


    /**
     * Execute the console command.
     *
     * @param ManagerInterface $manager
     * @return mixed
     */
    public function handle(ManagerInterface $manager): void
    {
        /**
         * @var ChannelInterface $channelData
         * @var SystemInterface $systemData
         */
        [$systemData, $channelData] = $this->findChannel($manager);

        /**
         * @var Channel $channel
         * @var System $system
         */
        [$system, $channel] = $this->findChannelInStorage($systemData, $channelData);


        $message = ($channel ? 'Updating' : 'Creating')
            . ' channel [' . $channelData->getId() . ': ' .$channelData->getName() . ']';
        $this->comment($message);


        if ($channel === null) {
            $channel = new Channel(['system_id' => $system->id]);
        }

        $this->sync($channel, $channelData);

        $this->info('Success');
    }

    /**
     * @param Channel $channel
     * @param ChannelInterface $data
     * @return Channel
     */
    private function sync(Channel $channel, ChannelInterface $data)
    {
        $channel->name = $data->getName();
        $channel->sys_channel_id = $data->getId();
        $channel->save();

        return $channel;
    }

    /**
     * @param ManagerInterface $manager
     * @return array
     */
    private function findChannel(ManagerInterface $manager): array
    {
        /** @var SystemInterface $system */
        $system = $manager->get($this->argument('system'));

        /** @var ChannelInterface $channel */
        $channel = $system->channel($this->argument('channel'));

        return [$system, $channel];
    }

    /**
     * @param SystemInterface $system
     * @param ChannelInterface $channel
     * @return array
     */
    private function findChannelInStorage(SystemInterface $system, ChannelInterface $channel): array
    {
        /** @var System $systemModel */
        $systemModel  = System::withName($system->getName())->first();

        /** @var Channel|null $channelModel */
        $channelModel = Channel::inSystem($systemModel)->withExternalId($channel->getId())->first();

        return [$systemModel, $channelModel];
    }
}
