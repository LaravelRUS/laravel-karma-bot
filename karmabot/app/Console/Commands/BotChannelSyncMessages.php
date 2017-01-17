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
use KarmaBot\Bot\Support\MemoryProfiler;
use KarmaBot\Model\Channel;
use KarmaBot\Model\Message;
use KarmaBot\Model\User;

/**
 * Class BotChannelAdd
 * @package KarmaBot\Console\Commands
 */
class BotChannelSyncMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:messages:sync 
        {channelId : Channel identifier}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load all messages from target channel';

    /**
     * Execute the console command.
     *
     * @param Container $container
     * @return void
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    public function handle(Container $container): void
    {
        $channel = Channel::find($this->argument('channelId'));

        if (!$channel) {
            throw new \InvalidArgumentException('Invalid channel id');
        }

        $conn = $channel->getChannelConnection($container);

        $profiler = new MemoryProfiler('Import messages');
        $profiler->setOutput(function ($message) {
            $this->output->newLine();
            $this->info($message);
        });

        foreach ($conn->messages() as $i => $received) {
            $profiler->check('Next message');

            $user = User::whereExternalUser($channel->system, $received->getUser())->first();
            $profiler->check('Serach user');

            if (!$user) {
                $user = User::new($received->getUser());
                $user->save();

                $profiler->check('Create user');

                $user->systems()->save($channel->system, [
                    'sys_user_id' => $received->getUser()->getId(),
                ]);

                $profiler->check('Add user <-> system relation');
            }

            $message = Message::whereExternalMessage($received, $channel, $user)->first();
            $profiler->check('Search message');

            if (!$message) {
                try {
                    $message = Message::new($received, $channel, $user);
                    $message->save();

                    $profiler->check('Create message');
                } catch (\Throwable $e) {
                    $this->error($e->getMessage());
                }
            }

            // Hard memory optimisation
            unset($user, $message);

            $this->output->write('.');
        }
    }
}
