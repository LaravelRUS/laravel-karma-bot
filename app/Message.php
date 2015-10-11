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
namespace App;

use Carbon\Carbon;
use LogicException;
use App\Gitter\Client;
use App\Mappers\MessageMapperTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Message
 * @package App
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
 */
class Message extends Model
{
    /**
     * Gitter mapper
     */
    use MessageMapperTrait;

    /**
     * @param array $options
     * @throws LogicException
     * @return void
     */
    final public function save(array $options = [])
    {
        throw new LogicException('Can not save message');
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
     * @param $text
     * @return $this
     */
    public function write($text)
    {
        $client = \App::make(Client::class);
        $room   = \App::make(Room::class);

        $client->request('message.send', ['roomId' => $room->id], [
            'text' => (string)$text,
        ], 'POST');

        return $this;
    }

    /**
     * @param $text
     * @return Model
     */
    public function pre($text)
    {
        return $this->write($this->decorate('`', $text));
    }

    /**
     * @param $code
     * @param string $lang
     * @return Model
     */
    public function code($code, $lang = '')
    {
        return $this->write(
            '```' . $lang . "\n" . $code . "\n" . '```'
        );
    }

    /**
     * @param $text
     * @return Model
     */
    public function italic($text)
    {
        return $this->write($this->decorate('_', $text));
    }

    /**
     * @param $text
     * @return Model
     */
    public function bold($text)
    {
        return $this->write($this->decorate('**', $text));
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
