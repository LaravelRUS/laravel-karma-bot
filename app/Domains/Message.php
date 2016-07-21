<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @author butschster <butschster@gmail.com>
 * @date 09.10.2015 16:58
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains;

use Carbon\Carbon;
use Domains\Message\FormatterInterface;
use Domains\Room\RoomInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Message
 * @deprecated 
 *
 * @property string $gitter_id
 * @property string $text
 * @property string $html
 * @property bool $edited
 * @property User $user
 * @property string $unread
 * @property int $read_by
 * @property array $urls
 * @property User[] $mentions
 * @property array $issues
 * @property array $meta
 * @property string $room_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read string $escaped_text
 * @property-read string $text_without_special_chars
 *
 */
class Message extends Model implements FormatterInterface
{
    /**
     * @var RoomInterface
     */
    protected $room;

    /**
     * Message constructor.
     *
     * @param array         $attributes
     * @param RoomInterface $room
     */
    public function __construct(array $attributes, RoomInterface $room)
    {
        parent::__construct($attributes);

        $this->room = $room;
    }

    /**
     * @param $value
     * @return Carbon
     */
    public function getUpdatedAtAttribute($value)
    {
        if ($value === null) {
            return $this->created_at;
        }

        return $value;
    }

    /**
     * @param callable $cb
     * @return bool
     */
    public function hasMention(callable $cb)
    {
        foreach ($this->mentions as $mention) {
            if ($cb($mention)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return mixed|string
     */
    public function getEscapedTextAttribute()
    {
        $text = $this->text;
        $text = mb_strtolower($text);
        $text = str_replace(["\n", "\r"], ' ', $text);
        $text = trim($text);

        return $text;
    }

    /**
     * @return mixed|string
     */
    public function getTextWithoutSpecialCharsAttribute()
    {
        $escapedText = $this->escaped_text;
        $escapedText = preg_replace('/\@[a-z0-9\-_]+/iu', '', $escapedText);
        $escapedText = preg_replace('/[^\s\w]/iu', '', $escapedText);
        $escapedText = trim($escapedText);

        return $escapedText;
    }

    /**
     * @param $text
     * @return $this
     */
    public function answer($text)
    {
        $this->room->answer($text);

        return $this;
    }

    /**
     * @param $text
     * @return Model
     */
    public function pre($text)
    {
        $this->room->pre($text);

        return $this;
    }

    /**
     * @param $code
     * @param string $lang
     * @return Model
     */
    public function code($code, $lang = '')
    {
        $this->room->code($code, $lang);

        return $this;
    }

    /**
     * @param $text
     * @return Model
     */
    public function italic($text)
    {
        $this->room->italic($text);

        return $this;
    }

    /**
     * @param $text
     * @return Model
     */
    public function bold($text)
    {
        $this->room->bold($text);

        return $this;
    }
}
