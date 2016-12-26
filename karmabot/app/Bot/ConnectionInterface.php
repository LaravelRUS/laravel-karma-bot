<?php declare(strict_types=1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Bot;

/**
 * Interface ConnectionInterface
 * @package KarmaBot\Bot
 */
interface ConnectionInterface
{
    /**
     * @param string $message
     */
    public function send(string $message): void;

    /**
     * @param \Closure $callback
     */
    public function subscribe(\Closure $callback): void;
}
