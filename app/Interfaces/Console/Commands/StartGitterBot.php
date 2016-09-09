<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @author butschster <butschster@gmail.com>
 *
 * @date 24.09.2015 15:27
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Interfaces\Console\Commands;


use Carbon\Carbon;
use Domains\RoomManager;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\Container;
use Domains\Room\RoomInterface;

/**
 * Class StartGitterBot
 */
class StartGitterBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gitter:listen {room}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start gitter bot thread for target room.';

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var string
     */
    protected $pid;

    /**
     * @param Container $app
     * @param RoomManager $manager
     */
    public function handle(Container $app, RoomManager $manager)
    {
        $roomId = $this->argument('room');

        if (!($room = $manager->get($roomId))) {
            $this->warn(sprintf('Room [%s] not found', $roomId));
            return;
        }

        $client = $room->client();
        $room->listen();

        $this->info(sprintf('%s started at %s', $client->version(), Carbon::now()));

        $this->makePidFile();

        $client->run();

        $this->removePidFile();
    }

    /**
     * Create pid file
     */
    protected function makePidFile()
    {
        $this->pid = storage_path('pids/' . date('Y_m_d_tis_') . microtime(1) . '.pid');
        file_put_contents($this->pid, getmypid());
    }

    /**
     * Delete pid file
     */
    protected function removePidFile()
    {
        if (is_file($this->pid)) {
            unlink($this->pid);
        }
    }
}
