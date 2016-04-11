<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.04.2016 20:48
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Message;

use Core\Entity\Getters;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 *
 * @property-read string $escaped
 * @property-read string $words
 * @property-read string $withoutSpecialChars
 */
class Text
{
    use Getters;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    public $text;

    /**
     * Text constructor.
     * @param string $text
     */
    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * @param string $text
     * @return $this|Text
     */
    public function update(string $text) : Text
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return Text
     */
    public function getEscaped() : Text
    {
        $text = mb_strtolower($this->text);
        $text = str_replace(["\n", "\r"], ' ', $text);
        return new Text(trim($text));
    }

    /**
     * @return Text
     */
    public function getWithoutSpecialChars() : Text
    {
        $text = (string)$this->getEscaped();

        $removes = [
            '/\@[a-z0-9\-_]+/iu',
            '/[^\s\w]/iu',
        ];

        foreach ($removes as $pattern) {
            $text = preg_replace($pattern, '', $text);
        }

        return new Text(trim($text));
    }

    /**
     * @return array
     */
    public function getWords() : array
    {
        $text = $this->text;

        $removes = [
            '/```.*?```/su',
            '/`.*?`/su',
            '/([a-z]{2,5}:\/\/[a-z]+\.[a-z]{2,}[\w\/\?=%#\-&:\$\.\+\!\*]+)(?:\s|\n)/iu',
            '/@[a-z0-9_@]+/iu',
        ];

        foreach ($removes as $pattern) {
            $text = preg_replace($pattern, '', $text);
        }

        preg_match_all('/\w+/iu', $text, $words, PREG_PATTERN_ORDER);

        return array_map(function ($word) {
            return mb_strtolower($word);
        }, $words[0]);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->text;
    }
}
