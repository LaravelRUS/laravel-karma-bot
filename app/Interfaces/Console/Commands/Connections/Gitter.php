<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 15:08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Interfaces\Console\Commands\Connections;

use Core\Io\IoInterface;
use Gitter\Client;
use Illuminate\Config\Repository;
use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Interfaces\Gitter\GitterIo;

/**
 * Class Gitter
 * @package Interfaces\Console\Commands\Connections
 */
class Gitter extends Command
{
    /**
     * @var string
     */
    protected $signature = 'connect:gitter';

    /**
     * @var string
     */
    protected $description = 'Start gitter listener';

    /**
     * @param Container $app
     * @param Repository $config
     * @param IoInterface $io
     * @throws \Exception
     * @throws \RuntimeException
     */
    public function handle(Container $app, Repository $config, IoInterface $io)
    {
        $client = new Client($config->get('gitter.token'));

        $gitter = new GitterIo($app, $client, $io);

        $gitter->run();
    }
}