<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 15.04.2016 19:42
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Doctrine;

use Doctrine\DBAL\Logging\SQLLogger as LoggerInterface;

/**
 * Class SqlMemoryLogger
 * @package Core\Doctrine
 */
class SqlMemoryLogger extends Memory implements LoggerInterface
{
    const MEMORY_SYNC_PATH = 'memory/sql-logger.sync';
    const MEMORY_KEY = 'sql';

    /**
     * SqlLogger constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct(storage_path(static::MEMORY_SYNC_PATH));
        $this->open()->set(static::MEMORY_KEY, []);
    }

    /**
     * @param string $sql
     * @param array|null $params
     * @param array|null $types
     * @throws \Exception
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        $this->open()->lock();

        $data = $this->open()->get(static::MEMORY_KEY);
        $data[] = $sql;
        $this->open()->set(static::MEMORY_KEY, $data);

        $this->open()->unlock();
    }

    public function stopQuery()
    {
        //
    }
}
