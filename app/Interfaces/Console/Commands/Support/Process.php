<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 19.04.2016 17:39
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Interfaces\Console\Commands\Support;


/**
 * Class Process
 * @package Interfaces\Console\Commands\Support
 */
class Process
{
    /**
     * @var string
     */
    private $command = '';

    /**
     * @var
     */
    private $process;

    /**
     * @var ProcessId
     */
    private $pid;

    /**
     * @var array
     */
    private $pipes = [];

    /**
     * Process constructor.
     * @param $command
     */
    public function __construct($command)
    {
        $this->command = $command;
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        return stream_get_contents($this->pipes[1]);
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @return $this
     */
    public function start()
    {
        $descriptor = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
        ];

        $cmd   = sprintf('php "%s" %s', base_path('artisan'), $this->command);

        $this->process = proc_open($cmd, $descriptor, $this->pipes);

        $this->pid = new ProcessId($this->getPid());
        $this->pid->create();

        return $this;
    }

    /**
     * @return array
     */
    public function status() : array
    {
        return proc_get_status($this->process);
    }

    /**
     * @return bool
     */
    public function isRunning() : bool
    {
        return $this->status()['running'];
    }

    /**
     * @return int
     */
    public function getPid() : int
    {
        return $this->status()['pid'];
    }

    /**
     * @return int
     */
    public function stop() : int
    {
        proc_close($this->process);
        $this->pid->delete();
    }
}