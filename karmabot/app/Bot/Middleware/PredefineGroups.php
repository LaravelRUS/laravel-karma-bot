<?php declare(strict_types=1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Bot\Middleware;

/**
 * Class PredefineGroups
 * @package KarmaBot\Bot\Middleware
 */
final class PredefineGroups
{
    /**
     * Middleware without group
     * @var string
     */
    public const GROUP_DEFAULT = 'Common';

    /**
     * System middleware (ping, status and other)
     * @var string
     */
    public const GROUP_SYSTEM = 'System';
}
