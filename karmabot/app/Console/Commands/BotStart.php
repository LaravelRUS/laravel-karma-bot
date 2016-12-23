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
use KarmaBot\Bot\ChannelConnection;
use KarmaBot\Model\Channel;
use KarmaBot\Model\System;
use React\EventLoop\LoopInterface;

/**
 * Class BotStart
 * @package KarmaBot\Console\Commands
 */
class BotStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Startup bot';


    /**
     * Execute the console command.
     *
     * @param Container $container
     * @param LoopInterface $loop
     * @return void
     */
    public function handle(Container $container, LoopInterface $loop): void
    {
        /** @var System $system */
        foreach (System::all() as $system) {
            $this->info('Join system ' . $system->title . ' (' . $system->name . ')');

            /** @var Channel $channel */
            foreach ($system->channels as $channel) {
                $container->make(ChannelConnection::class, [$channel]);
                $this->comment('    + Channel [' . $channel->id . ': ' . $channel->name . ']');
            }
        }

        $loop->run();

        $this->error('Stopping event loop');
    }

}
