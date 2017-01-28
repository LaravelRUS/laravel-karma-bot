<?php declare(strict_types = 1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Collection;
use App\Console\Render\Table;
use App\Model\Channel;
use App\Model\System;
use Serafim\KarmaCore\Io\ChannelInterface;

/**
 * Class BotChannelAdd
 * @package App\Console\Commands
 */
class BotChannelList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:channels 
        {systemId? : System identifier}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show all available channels';

    /**
     * Execute the console command.
     *
     * @param Container $container
     * @return void
     * @internal param ManagerInterface $manager
     */
    public function handle(Container $container): void
    {

        /** @var System $system */
        foreach ($this->getSystems() as $system) {
            $table = new Table($this->output, 'System "' . $system->title . '" with driver "' . $system->driver . '"');


            /** @var ChannelInterface[] $channels */
            $channels = $system->getSystemConnection($container)->channels();
            $this->description($table);

            /** @var ChannelInterface $channel */
            foreach ($channels as $i => $channel) {
                /** @var Channel $channelModel */
                $model = Channel::inSystem($system)
                    ->withExternalId((string)$channel->getId())
                    ->first();

                $table->render([
                    $model ? $model->id : '' => 5,
                    $channel->getId()        => 30,
                    $channel->getName()      => 30,
                ], ['info']);

                if (($i + 1) % 50 === 0) {
                    $this->description($table, true);
                }
            }

            $this->description($table, true);
        }
    }

    /**
     * @return System[]|Collection
     */
    private function getSystems()
    {
        if (!$this->argument('systemId')) {
            return System::all();
        }

        return System::where('id', (int)$this->argument('systemId'))->get();
    }

    /**
     * @param Table $table
     * @param bool $prefix
     */
    private function description(Table $table, $prefix = false)
    {
        if ($prefix) {
            $table->delimiter(69);
        }
        $table->render(['ID' => 5, 'External id' => 30, 'Name' => 30], [null, null, null]);
        $table->delimiter(69);
    }
}
