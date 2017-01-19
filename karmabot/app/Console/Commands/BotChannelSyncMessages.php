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
use Serafim\KarmaCore\Io\ReceivedMessageInterface;

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
     * @var MemoryProfiler
     */
    private $mem;

    /**
     * BotChannelSyncMessages constructor.
     */
    public function __construct()
    {
        $this->mem = new MemoryProfiler('Import messages');

        $fpd = null;
        $fps = null;

        $this->mem->setOutput(function ($delta, $sum) use (&$fpd, &$fps) {
            if (!$fps) {
                $fps = fopen(base_path('../profiler/memory/' . date('Y-m-d H:i:s') . '-memory_usage.txt'), 'a+b');
            }

            fwrite($fps, $sum . "\n");
        });

        parent::__construct();
    }

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

        foreach ($conn->messages() as $i => $received) {
            $this->import($channel, $received, $i);
        }
    }

    /**
     * @param Channel $channel
     * @param ReceivedMessageInterface $received
     * @param $i
     */
    private function import(Channel $channel, ReceivedMessageInterface $received, $i): void
    {
        $this->mem->check('Next message');

        $user = $this->checkUser($channel, $received);

        $this->importMessage($channel, $received, $user);

        unset($message, $user, $received);

        $this->output->write('.');
    }

    /**
     * @param Channel $channel
     * @param ReceivedMessageInterface $received
     * @return User
     */
    private function checkUser(Channel $channel, ReceivedMessageInterface $received)
    {
        $user = User::whereExternalUser($channel->system, $received->getUser())->first();
        $this->mem->check('Serach user');

        if (!$user) {
            $user = User::new($received->getUser());
            $user->save();

            $this->mem->check('Create user');

            $user->systems()->save($channel->system, [
                'sys_user_id' => $received->getUser()->getId(),
            ]);

            $this->mem->check('Add user <-> system relation');
        }

        return $user;
    }

    /**
     * @param Channel $channel
     * @param ReceivedMessageInterface $received
     * @param User $user
     * @return bool
     */
    private function importMessage(Channel $channel, ReceivedMessageInterface $received, User $user)
    {
        $message = Message::whereExternalMessage($received, $channel, $user)->first();
        $this->mem->check('Search message');

        if (!$message) {
            try {
                $message = Message::new($received, $channel, $user);
                $message->save();

                $this->mem->check('Create message');
            } catch (\Throwable $e) {
                $this->error($e->getMessage());
            }

            return true;
        }

        return false;
    }
}
