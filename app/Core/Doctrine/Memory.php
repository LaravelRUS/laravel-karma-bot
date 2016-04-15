<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 15.04.2016 19:46
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Doctrine;

use Fuz\Component\SharedMemory\SharedMemory;
use Fuz\Component\SharedMemory\Storage\StorageFile;

/**
 * Class Memory
 * @package Core\Doctrine
 */
class Memory
{
    /**
     * @var string
     */
    private $sync;

    /**
     * @var StorageFile
     */
    private $storage;

    /**
     * @var SharedMemory
     */
    private $memory;

    /**
     * Memory constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->sync = $path;
        $this->storage = new StorageFile($this->sync);
        $this->memory = new SharedMemory($this->storage);
    }

    /**
     * @return SharedMemory
     */
    public function open()
    {
        return $this->memory;
    }
}
