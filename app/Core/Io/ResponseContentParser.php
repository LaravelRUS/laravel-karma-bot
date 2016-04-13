<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 13.04.2016 15:50
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Io;

use Domains\Message\Message;
use Domains\Message\Text;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class ResponseContentParser
 * @package Core\Io
 */
trait ResponseContentParser
{
    /**
     * @param $data
     * @return string
     */
    protected function parseAnswer($data) : string
    {
        switch (true) {
            case $data instanceof Text:
                return (string)$data->text;

            case $data instanceof Message:
                return (string)$data->text->text;

            case $data instanceof Renderable:
                return (string)$data->render();

            case $data instanceof Jsonable:
                return (string)$data->toJson();

            case $data instanceof Arrayable:
                return json_encode($data->toArray());

            case $data instanceof \Throwable:
                return (string)null;

            case is_object($data) && method_exists($data, '__toString'):
                return (string)$data;

            case is_array($data):
                return (string)json_encode($data);
        }

        return (string)$data;
    }
}
