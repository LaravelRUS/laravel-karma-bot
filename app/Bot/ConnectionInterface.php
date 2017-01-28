<?php declare(strict_types=1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Bot;

use React\EventLoop\LoopInterface;
use Serafim\KarmaCore\Io\ReceivedMessageInterface;

/**
 * Interface ConnectionInterface
 * @package App\Bot
 */
interface ConnectionInterface
{
    /**
     * @param string $message
     * @return void
     */
    public function send(string $message): void;

    /**
     * @param \Closure $callback
     * @return void
     */
    public function subscribe(\Closure $callback): void;

    /**
     * @param ReceivedMessageInterface $inputMessage
     */
    public function onMessage(ReceivedMessageInterface $inputMessage): void;
}
