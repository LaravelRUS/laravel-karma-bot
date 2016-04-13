<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 13.04.2016 15:26
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Io;

/**
 * Class InclusionTrigger
 * @package Core\Io
 */
trait InclusionTrigger
{
    /**
     * @var bool
     */
    private $enabled = true;

    /**
     * @return bool
     */
    public function isDisabled() : bool
    {
        return !$this->isEnabled();
    }

    /**
     * @return bool
     */
    public function isEnabled() : bool
    {
        return $this->enabled;
    }

    /**
     * @return $this|Inclusion
     */
    public function disable() : Inclusion
    {
        $this->enabled = false;
        return $this;
    }

    /**
     * @return $this|Inclusion
     */
    public function enable() : Inclusion
    {
        $this->enabled = true;
        return $this;
    }
}
