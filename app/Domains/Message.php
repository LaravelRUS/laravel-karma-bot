<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 09.10.2015 16:58
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains;

use Carbon\Carbon;
use LogicException;
use Interfaces\Gitter\Client;
use Core\Mappers\MessageMapperTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Message
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
class Message extends Model
{
    /**
     * Gitter mapper
     */
    use MessageMapperTrait;

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
        if (\Config::get('gitter.output')) {
            $client = \App::make(Client::class);
            $room = \App::make(Room::class);

            $client->request('message.send', ['roomId' => $room->id], [
                'text' => (string)$text,
            ], 'POST');
        }

        return $this;
    }

    /**
     * @param $text
     * @return Model
     */
    public function pre($text)
    {
        return $this->answer($this->decorate('`', $text));
    }

    /**
     * @param $code
     * @param string $lang
     * @return Model
     */
    public function code($code, $lang = '')
    {
        return $this->answer(
            '```' . $lang . "\n" . $code . "\n" . '```'
        );
    }

    /**
     * @param $text
     * @return Model
     */
    public function italic($text)
    {
        return $this->answer($this->decorate('_', $text));
    }

    /**
     * @param $text
     * @return Model
     */
    public function bold($text)
    {
        return $this->answer($this->decorate('**', $text));
    }

    /**
     * @param $symbol
     * @param $text
     * @return string
     */
    protected function decorate($symbol, $text)
    {
        $result = [];
        $strings = explode("\n", $text);
        foreach ($strings as $string) {
            $result[] =
                $symbol .
                str_replace($symbol, '\\' . $symbol, trim($string)) .
                $symbol;
        }

        return implode("\n", $result);
    }
}
