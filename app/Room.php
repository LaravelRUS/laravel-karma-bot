<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 09.10.2015 17:08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App;

use App\Gitter\Client;
use App\Gitter\Http\Stream;
use InvalidArgumentException;

/**
 * Class Room
 * @package App
 *
 * @property int $id
 */
class Room
{
    /**
     * @param $roomId
     * @return mixed
     */
    public static function getId($roomId)
    {
        $rooms = \Config::get('gitter.rooms');
        $alias = mb_strtolower(trim($roomId));

        if (array_key_exists($alias, $rooms)) {
            return $rooms[$alias];
        }
        return $roomId;
    }

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    public $id;

    /**
     * Room constructor.
     * @param Client $client
     * @param string $roomId
     *
     */
    public function __construct(Client $client, $roomId)
    {
        $this->client = $client;
        $this->id = $roomId;
    }

    /**
     * @throws InvalidArgumentException
     * @return Client
     */
    public function listen()
    {
        $client = $this->client
            ->stream('messages', ['roomId' => $this->roomId])
            ->on(Stream::EVENT_MESSAGE, function($stream, $data) {
                $this->onMessage(Message::fromGitterObject($data));
            })
            ->on(Stream::EVENT_END, [$this, 'onClose'])
            ->on(Stream::EVENT_ERROR, [$this, 'onError']);

        return $client;
    }

    /**
     * @param Message $message
     */
    public function onMessage(Message $message)
    {
        var_dump($message);
    }

    /**
     * @TODO I do not know if it works
     * @param Stream $stream
     */
    public function onClose(Stream $stream)
    {
        $stream->reconnect();
    }

    /**
     * @TODO I do not know if it works
     * @param Stream $stream
     */
    public function onError(Stream $stream)
    {
        $stream->reconnect();
    }
}
