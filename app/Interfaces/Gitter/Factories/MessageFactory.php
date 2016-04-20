<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.04.2016 20:19
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Interfaces\Gitter\Factories;

use Carbon\Carbon;
use Core\Repositories\Message\MessagesRepository;
use Core\Repositories\Services\GitterServiceRepository;
use Domains\Message\Message;
use Domains\User\Mention;

/**
 * Class MessageFactory
 * @package Interfaces\Gitter\Factories
 */
class MessageFactory extends ServiceFactory
{
    /**
     * @var UserFactory
     */
    private $users;

    /**
     * @var MessagesRepository
     */
    private $messages;

    /**
     * MessageFactory constructor.
     * @param GitterServiceRepository $services
     * @param MessagesRepository $messages
     * @param UserFactory $users
     */
    public function __construct(GitterServiceRepository $services, MessagesRepository $messages, UserFactory $users)
    {
        parent::__construct($services);

        $this->messages = $messages;
        $this->users = $users;
    }

    /**
     * @param \StdClass $data
     * @param string $roomId
     * @param bool $sync
     * @return Message
     */
    public function fromMessage($data, string $roomId, $sync = false) : Message
    {
        $service = $this->fromServiceId($data->id);

        /** @var Message $message */
        $message = $this->messages->find($service->id);

        if (!$message) {
            $user = $this->users->fromUser($data->fromUser, true);

            $message = Message::create([
                'id'         => $service->id,
                'room_id'    => $roomId,
                'user_id'    => $user->id,
                'text'       => $data->text,
                'created_at' => new Carbon($data->sent),
                'updated_at' => new Carbon($data->editedAt ?? $data->sent),
            ]);
        }

        if (($data->mentions ?? []) !== []) {
            foreach ($data->mentions as $mentionData) {
                if (!$this->users->isValidMention($mentionData)) {
                    continue;
                }

                $user = $this->users->fromMention($mentionData);

                try {
                    Mention::create([
                        'user_id'        => $message->getAttribute('user_id'),
                        'user_target_id' => $user->id,
                        'message_id'     => $message->id,
                    ]);
                } catch (\Exception $e) {
                    // Exception can be "Integrity constraint violation: 1062 Duplicate entry"
                    // Just ignore for fastest inserting
                }
            }
        }

        return $message;
    }

}
