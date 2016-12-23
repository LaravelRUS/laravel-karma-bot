<?php declare(strict_types=1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Bot;

use KarmaBot\Model\Channel;
use Serafim\KarmaCore\Io\AnswerInterface;
use Serafim\KarmaCore\Io\ChannelInterface;
use Serafim\KarmaCore\Io\ManagerInterface;

/**
 * Class ChannelConnection
 * @package KarmaBot\Bot
 */
class ChannelConnection
{
    /**
     * @var ChannelInterface
     */
    private $io;

    /**
     * @var Channel
     */
    private $channel;

    /**
     * Connection constructor.
     * @param Channel $channel
     * @param ManagerInterface $manager
     */
    public function __construct(Channel $channel, ManagerInterface $manager)
    {
        $this->io = $this->getIo($channel, $manager);
        $this->channel = $channel;

        $this->io->subscribe(function (AnswerInterface $message) {
            $this->onMessage($message);
        });
    }

    /**
     * @param Channel $channel
     * @param ManagerInterface $manager
     * @return ChannelInterface
     */
    private function getIo(Channel $channel, ManagerInterface $manager): ChannelInterface
    {
        return $manager->get($channel->system->driver)->channel($channel->sys_channel_id);
    }

    /**
     * @param AnswerInterface $answer
     */
    protected function onMessage(AnswerInterface $answer)
    {
        //
    }

    /**
     * @param string $message
     * @return \Generator|ChannelInterface[]
     */
    public function send(string $message): \Generator
    {
        $this->io->publish($message);
    }
}
