<?php declare(strict_types = 1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Bot\Middleware;

use Illuminate\Contracts\Container\Container;

/**
 * Class MiddlewareManager
 * @package KarmaBot\Bot\Middleware
 */
class Manager
{
    /**
     * @var array|MiddlewareInterface[]
     */
    private $middleware = [];

    /**
     * @var Container
     */
    private $container;

    /**
     * Manager constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string|MiddlewareInterface $middleware
     * @return $this|Manager
     * @throws \InvalidArgumentException
     */
    public function register(string $middleware): Manager
    {
        if (!$this->isClassOfMiddlewareInterface($middleware)) {
            throw new \InvalidArgumentException($middleware . ' must be implements ' . MiddlewareInterface::class);
        }

        $this->middleware[$middleware::getName()] = $middleware;

        return $this;
    }

    /**
     * @param string $class
     * @return bool
     */
    private function isClassOfMiddlewareInterface(string $class): bool
    {
        $instance = (new \ReflectionClass($class))->newInstanceWithoutConstructor();

        return $instance instanceof MiddlewareInterface;
    }

    /**
     * @param string $name
     * @param array $options
     * @return MiddlewareInterface
     * @throws \InvalidArgumentException
     */
    public function make(string $name, array $options = []): MiddlewareInterface
    {
        $class = $this->middleware[$name] ?? null;

        if ($class === null) {
            throw new \InvalidArgumentException('Invalid middleware ' . $name);
        }

        return $this->container->make($class, ['options' => $options]);
    }
}
