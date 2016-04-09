<?php
declare(strict_types = 1);
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 28.03.2016 14:06
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Message;

use Carbon\Carbon;
use Core\Mappers\Message\MessageMapper;
use Domains\Analyser\UrlFinder;

/**
 * Class Message
 * @package Domains\Message
 *
 * @property-read $text
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * @property-read array $words
 */
class Message extends MessageMapper
{
    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getWordsAttribute()
    {
        $text = $this->text;

        $text = preg_replace('/```.*?```/su', '', $text);
        $text = preg_replace('/`.*?`/su', '', $text);
        $text = preg_replace(UrlFinder::getPattern(), '', $text);
        $text = preg_replace('/@[a-z0-9_@]+/iu', '', $text);

        preg_match_all('/\w+/iu', $text, $words, PREG_PATTERN_ORDER);

        return array_map(function ($word) {
            return mb_strtolower($word);
        }, $words[0]);
    }
}
