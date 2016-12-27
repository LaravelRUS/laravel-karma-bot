<?php declare(strict_types=1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Middleware;

use KarmaBot\Bot\Middleware\AbstractMiddleware;
use KarmaBot\Bot\Middleware\PredefineGroups;
use Serafim\KarmaCore\Io\ReceivedMessageInterface;

/**
 * Class Ping
 * @package KarmaBot\Middleware
 */
class Ping extends AbstractMiddleware
{
    /**
     * @var string
     */
    protected $group = PredefineGroups::GROUP_SYSTEM;

    /**
     * @param ReceivedMessageInterface $input
     * @param \Closure $next
     * @return mixed
     */
    public function handle(ReceivedMessageInterface $input, \Closure $next)
    {
        if ($input->getBody() === 'ping') {
            return 'pong';
        }

        return $next($input);
    }
}
