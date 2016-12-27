<?php declare(strict_types = 1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Container\Container;
use Illuminate\Log\Writer;
use KarmaBot\Bot\SingleChannel;
use KarmaBot\Model\Channel;
use KarmaBot\Model\System;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;

/**
 * Class BotListen
 * @package KarmaBot\Console\Commands
 */
class BotListen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:listen 
        {systemId : System id}
        {channelId : Channel id}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Startup connection for system.id and registered channel.id';


    /**
     * @param LoopInterface $loop
     * @param Container $app
     * @return void
     * @throws \InvalidArgumentException
     */
    public function handle(LoopInterface $loop, Container $app): void
    {
        $system = System::find($this->argument('systemId'));
        if (!$system) {
            throw new \InvalidArgumentException('System not found');
        }

        $channel = Channel::inSystem($system)
            ->whereId($this->argument('channelId'))
            ->first();

        if (!$channel) {
            throw new \InvalidArgumentException('Channel not found');
        }

        $this->addOutputLogger($app);

        $connection = new SingleChannel($app, $channel);

        $loop->run();
    }

    /**
     * @param Container $app
     */
    private function addOutputLogger(Container $app)
    {
        /** @var Writer $writer */
        $writer = $app->make(LoggerInterface::class);
        $writer->getMonolog()->pushHandler(new StreamHandler(STDOUT));
    }
}
