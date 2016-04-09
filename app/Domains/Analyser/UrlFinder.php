<?php
declare(strict_types = 1);
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 28.03.2016 23:19
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Domains\Analyser;

use Core\Lazy\Fetch;
use Domains\Message\Message;
use Domains\Message\Url;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UrlFinder
 * @package Domains\Analyser
 */
class UrlFinder implements Analyser
{
    /**
     * @var Model
     */
    private $entity;

    /**
     * @var string
     */
    private $table;

    /**
     * UrlAnalyser constructor.
     */
    public function __construct()
    {
        $this->entity = new Url;
        $this->table = $this->entity->getTable();
    }

    /**
     * @return $this|Analyser
     * @throws \Exception
     */
    public function clear() : Analyser
    {
        $this->entity->delete();
        return $this;
    }

    /**
     * @param \Closure|null $progress
     * @return $this|Analyser
     */
    public function analyse(\Closure $progress = null) : Analyser
    {
        // Add hidden urls
        $response = new Fetch(Message::query());

        /** @var Message $message */
        foreach ($response as $i => $message) {
            $matches = $this->getUrls($message->text);

            if (count($matches)) {
                $urls = [];
                foreach ($matches as $url) {
                    $urls[] = new Url(['url' => $url]);
                }

                $message->urls()->saveMany($urls);

                if ($progress !== null) {
                    $progress($message, $matches, $i++);
                }
            }
        }

        return $this;
    }

    /**
     * @param string $text
     * @return mixed
     */
    private function getUrls(string $text)
    {
        // TODO Add support for non-latin domains
        // Current RFC 1738
        $pattern = static::getPattern();
        preg_match_all($pattern, $text . ' ', $matches, PREG_PATTERN_ORDER);

        return $matches[1];
    }

    /**
     * @return string
     */
    public static function getPattern()
    {
        return '/([a-z]{2,5}:\/\/[a-z]+\.[a-z]{2,}[\w\/\?=%#\-&:\$\.\+\!\*]+)(?:\s|\n)/iu';
    }
}
