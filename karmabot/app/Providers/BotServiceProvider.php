<?php declare(strict_types = 1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Providers;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Config\Repository as RepositoryInterface;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use KarmaBot\Model\System;
use Psr\Log\LoggerInterface;
use Serafim\KarmaCore\Application;
use Serafim\KarmaCore\Io\ManagerInterface;
use Serafim\KarmaCore\Io\SystemInterface;
use Serafim\KarmaCore\System\Gitter\GitterSystem;
use Serafim\KarmaCore\System\Slack\SlackSystem;
use Serafim\KarmaCore\System\SystemInformation;
use Serafim\MessageComponent\Adapter\AdapterInterface;
use Serafim\MessageComponent\Adapter\GitterAdapter;
use Serafim\MessageComponent\Adapter\SlackAdapter;
use Serafim\MessageComponent\Manager;

/**
 * Class BotServiceProvider
 * @package KarmaBot\Providers
 */
class BotServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(Manager::class);
    }

    /**
     * @param ManagerInterface $manager
     * @param LoggerInterface $logger
     */
    public function boot(ManagerInterface $manager, LoggerInterface $logger): void
    {
        try {
            $systems = System::all();

            /** @var System $system */
            foreach ($systems as $system) {
                $manager->register($system->name, $this->getSystem($system));
            }
        } catch (\Throwable $e) {
            $logger->critical($e->getMessage());
        }
    }

    /**
     * @param System $system
     * @return SystemInterface
     * @throws \InvalidArgumentException
     */
    private function getSystem(System $system): SystemInterface
    {
        $factory = function (string $class) use ($system): SystemInterface {
            return new $class($this->getSystemInfo($system));
        };


        switch ($system->driver) {
            case 'gitter':
                return $factory(GitterSystem::class);
            case 'slack':
                return $factory(SlackSystem::class);
        }


        throw new \InvalidArgumentException('Can not find available system adapter');
    }

    /**
     * @param System $system
     * @return SystemInformation
     * @throws \InvalidArgumentException
     */
    private function getSystemInfo(System $system): SystemInformation
    {
        $adapter = $this->getMessageAdapter($system);

        $container = $this->app->make(Application::class);

        return new class($system, $adapter, $container) extends SystemInformation
        {
            /**
             * @var System
             */
            private $system;

            /**
             * class@anonymous constructor.
             * @param System $system
             * @param AdapterInterface $adapter
             * @param Container $container
             */
            public function __construct(System $system, AdapterInterface $adapter, Container $container)
            {
                $this->system = $system;
                parent::__construct($system->name, $adapter, $container);
            }

            /**
             * @return RepositoryInterface
             */
            final public function config(): RepositoryInterface
            {
                return new Repository([ 'token' => $this->system->token ]);
            }
        };
    }

    /**
     * @param System $system
     * @return AdapterInterface
     * @throws \InvalidArgumentException
     */
    private function getMessageAdapter(System $system): AdapterInterface
    {
        /** @var Manager $manager */
        $manager = $this->app->make(Manager::class);

        switch ($system->driver) {
            case 'gitter':
                return $manager->get(GitterAdapter::class);
            case 'slack':
                return $manager->get(SlackAdapter::class);
        }

        throw new \InvalidArgumentException('Can not find available message component adapter');
    }
}
