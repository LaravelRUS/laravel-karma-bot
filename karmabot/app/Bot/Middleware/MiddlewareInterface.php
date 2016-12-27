<?php declare(strict_types=1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Bot\Middleware;
use Serafim\KarmaCore\Io\ReceivedMessageInterface;

/**
 * Interface MiddlewareInterface
 * @package KarmaBot\Bot\Middleware
 */
interface MiddlewareInterface
{
    /**
     * Skip all self messages
     * @var int
     */
    public const FLOW_LOGIC_SKIP_SELF = 1;

    /**
     * All messages
     * @var int
     */
    public const FLOW_LOGIC_ALL = 2;

    /**
     * @return string
     */
    public function getGroup(): string;

    /**
     * @return string
     */
    public static function getName(): string;

    /**
     * @return int
     */
    public function getFlowType(): int;

    /**
     * @param ReceivedMessageInterface $input
     * @param \Closure $next
     * @return mixed
     */
    public function handle(ReceivedMessageInterface $input, \Closure $next);
}
