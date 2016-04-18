<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 09.10.2015 16:35
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Bot\Middlewares\Karma\Validation;

use Core\Repositories\KarmaRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Domains\Karma\Karma;
use Domains\Message\Message;
use Domains\Message\Text;
use Domains\User\Mention;
use Domains\User\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class Validator
 * @package Domains\Bot\Middlewares\Karma
 */
class Validator
{
    /**
     * @var array
     */
    protected $likes = [];

    /**
     * @var KarmaRepository
     */
    private $karma;

    /**
     * Validator constructor.
     * @param KarmaRepository $repository
     */
    public function __construct(KarmaRepository $repository)
    {
        $this->likes = trans('thanks.likes');
        $this->karma = $repository;
    }

    /**
     * @param Message $message
     * @return Status[]|Collection
     */
    public function validate(Message $message)
    {
        $response = new Collection([]);

        // If has no mentions
        if (!$message->hasMentions()) {
            if ($this->validateText($message)) {
                $response->push(new Status($message->user, Status::STATUS_NO_USER));
            }

            return $response;
        }

        /** @var Mention $mention */
        foreach ($message->mentions as $mention) {
            $response->push($this->validateMessage($message, $mention->target));
        }

        return $response;
    }

    /**
     * @param Message $message
     * @return bool
     */
    protected function validateText(Message $message)
    {
        $text = $message->text->withoutSpecialChars;
        $text = (new Text($text))->toLower();

        return Str::endsWith($text, $this->likes) || Str::startsWith($text, $this->likes);
    }

    /**
     * @param Message $message
     * @param User $mention
     * @return Status
     */
    protected function validateMessage(Message $message, User $mention)
    {
        if ($this->validateText($message)) {
            if (!$this->validateUser($message, $mention)) {
                return new Status($mention, Status::STATUS_SELF);
            }

            if (!$this->validateTimeout($message, $mention)) {
                return new Status($mention, Status::STATUS_TIMEOUT);
            }

            return new Status($mention, Status::STATUS_INCREMENT);
        }

        return new Status($mention, Status::STATUS_NOTHING);
    }

    /**
     * @param Message $message
     * @param User $mention
     * @return bool
     */
    protected function validateUser(Message $message, User $mention)
    {
        return $mention->id !== $message->user->id;
    }

    /**
     * @param Message $message
     * @param User $mention
     * @return bool
     */
    protected function validateTimeout(Message $message, User $mention)
    {
        try {
            /** @var Karma $karma */
            $karma = $this->karma
                ->getLatestKarmaForUser($mention)
                ->setMaxResults(1)
                ->getSingleResult();

        } catch (NoResultException $e) {
            return true;
        } catch (NonUniqueResultException $e) {
            return true;
        }

        return $karma->created->getTimestamp() + 60 < $message->created->getTimestamp();
    }
}
