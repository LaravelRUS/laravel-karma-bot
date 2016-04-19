<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 13.04.2016 16:10
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Interfaces\Console\Commands\Support;

/**
 * Class ProcessId
 * @package Interfaces\Console\Commands\Support
 */
class ProcessId
{
    /**
     * @var int
     */
    private $current;

    /**
     * @var string
     */
    private $file;

    /**
     * Pid constructor.
     * @param null|int $pid
     */
    public function __construct($pid = null)
    {
        $this->current = $pid ?: getmygid();
        $this->file = $this->getPath(date('Y_m_d_tis_') . microtime(1));
    }

    /**
     * @param $name
     * @return string
     */
    private function getPath($name) : string
    {
        return storage_path('pids/' . $name . '.pid');
    }

    /**
     * @return bool
     */
    public function create() : bool
    {
        if (!is_dir(dirname($this->file))) {
            return false;
        }

        try {
            file_put_contents($this->file, $this->current);
        } catch (\Throwable $e) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function delete() : bool
    {
        if (is_file($this->file)) {
            try {
                unlink($this->file);
                return true;
            } catch (\Throwable $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function check() : bool
    {
        return true;
    }
}
