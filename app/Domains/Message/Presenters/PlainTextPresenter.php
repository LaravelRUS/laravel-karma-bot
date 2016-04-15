<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 15.04.2016 16:24
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Message\Presenters;

use Domains\Message\Message;
use Domains\Message\Text;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class PlainTextPresenter
 * @package Domains\Message\Presenters
 */
class PlainTextPresenter extends Presenter
{
    /**
     * @var string
     */
    private $text;

    /**
     * PlainTextPresenter constructor.
     * @param string $text
     */
    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function render() : string
    {
        $pattern = sprintf('/<(?:/)?%s>/isu', implode('|', static::$tags));

        return preg_replace($pattern, '', $this->text);
    }
}
