<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 12.04.2016 15:38
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Interfaces\Gitter;

use Core\Io\Bus;
use Core\Presenters\MarkdownPresenter;
use Domains\Message\Message;
use Domains\Room\Room;
use Domains\User\User;
use Gitter\Client;
use Illuminate\Support\Str;
use Interfaces\Gitter\Factories\Message as MessageFactory;
use Interfaces\Gitter\Factories\User as UserFactory;

/**
 * Class Io
 * @package Interfaces\Gitter
 */
class Io extends Bus
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Room
     */
    private $room;

    /**
     * @var null|User
     */
    private $authAs = null;

    /**
     * Response constructor.
     * @param Client $client
     * @param Room $room
     */
    public function __construct(Client $client, Room $room)
    {
        parent::__construct();

        $this->room = $room;
        $this->client = $client;
    }

    /**
     * @return void
     * @throws \LogicException
     */
    public function listen()
    {
        $this->client->stream->onMessage($this->room->id, function ($data) {
            $message = MessageFactory::create($data, $this->room);
            
            $this->fire(static::EVENT_NEW_MESSAGE, $message);
        });

        // TODO Implement new user event
        // TODO Implement new room event

        $this->client->stream->listen();
    }

    /**
     * @return User
     * @throws \Exception
     */
    public function auth() : User
    {
        if ($this->authAs === null) {
            $response = $this->client->http->getCurrentUser()->wait();

            $this->authAs = UserFactory::create($response[0]);
        }

        return $this->authAs;
    }

    /**
     * @param mixed $text
     * @return bool
     */
    public function send($text) : bool
    {
        if ($this->isDisabled()) {
            return true;
        }

        $text = $this->parseAnswer($text);

        if (trim($text)) {
            try {
                $decorated = $this->botAnswer($text);

                $this->client->http->sendMessage($this->room->id, $decorated)->wait();
            } catch (\Throwable $e) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $text
     * @return array
     */
    private function botAnswer(string $text)
    {
        $result = [];
        $lines = explode("\n", $text);
        $codeOpen = false;

        foreach ($lines as $line) {
            if (Str::startsWith($line, '```')) {
                $codeOpen = !$codeOpen;
            }

            if (!$codeOpen) {
                $result[] = '_' . $line . '_';
            }
        }

        return implode("\n", $result);
    }

    /**
     * @param Message $message
     * @param mixed $text
     * @return bool
     */
    public function update(Message $message, $text) : bool
    {
        if ($this->isDisabled()) {
            return true;
        }

        $text = $this->parseAnswer($text);

        try {
            $this->client->http->updateMessage($this->room->id, $message->id, $text)->wait();
        } catch (\Throwable $e) {
            return false;
        }

        return true;
    }
}
