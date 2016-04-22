<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 14:48
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Io;

use Core\Io\Commands\Command;
use React\Promise\Promise;

/**
 * Interface IoInterface
 * @package Core\Io
 */
interface IoInterface
{
    /**
     * @param string $name
     * @return EntityInterface
     */
    public function entity(string $name) : EntityInterface;

    /**
     * @param Command $command
     * @return Promise
     */
    public function command(Command $command) : Promise;

    /**
     * @param string $command
     * @return Promise
     */
    public function onCommand(string $command) : Promise;

    /**
     * @return void
     */
    public function run();
}