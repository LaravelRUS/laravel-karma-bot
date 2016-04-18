<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.04.2016 17:45
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Bot\Middlewares\Common;


use Core\Io\Bus;
use Domains\Bot\Middlewares\Common\GoogleSearch\GoogleSearch;
use Domains\Bot\Middlewares\Middleware;
use Domains\Message\Message;
use Domains\User\Bot;
use Domains\User\Mention;
use Illuminate\Config\Repository;
use Illuminate\Support\Collection;

/**
 * Class GoogleSearchMiddleware
 * @package Domains\Bot\Middlewares\Common
 */
class GoogleSearchMiddleware implements Middleware
{
    /**
     * @var GoogleSearch
     */
    private $search;

    /**
     * GoogleSearchMiddleware constructor.
     */
    public function __construct(Repository $config)
    {
        $this->search = new GoogleSearch($config);
    }

    /**
     * @param Bot $bot
     * @param Message $message
     * @return string|void
     */
    public function handle(Bot $bot, Message $message)
    {
        $query = $this->getGoogleQuery($message);

        if ($query) {
            $search = '';
            try {
                $search = $this->search->searchGetMessage($query);
            } catch (\Throwable $e) {}


            if (count($message->mentions)) {
                /** @var Mention $mentionTo */
                $mentionTo = $message->mentions->first();

                $answerTo = $mentionTo->isMentionOf($bot)
                    ? $message->user
                    : $mentionTo->user;

                return [
                    trans('google.personal', [
                        'user'  => $answerTo->credinals->login,
                        'query' => urlencode($query),
                    ]),
                    $search
                ];
            }

            return [
                trans('google.common', ['query' => urlencode($query)]),
                $search
            ];
        }
    }

    /**
     * @param Message $message
     * @return string
     */
    private function getGoogleQuery(Message $message) : string
    {
        $words = (new Collection(trans('google.queries')))->map('preg_quote')->implode('|');
        $pattern = sprintf('/^(?:@.*?\s)?(?:%s)\s(.*?)$/isu', $words);
        $found = preg_match($pattern, $message->text->inline, $matches);

        if ($found) {
            return trim($matches[1]);
        }

        return '';
    }
}
