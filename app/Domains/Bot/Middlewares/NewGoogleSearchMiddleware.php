<?php
namespace Domains\Bot\Middlewares;

use Domains\Message;
use Domains\Middleware\MiddlewareInterface;
use Illuminate\Config\Repository;
use Illuminate\Support\Collection;
use Interfaces\Google\GoogleSearch;

/**
 * Class GoogleSearchMiddleware
 */
class NewGoogleSearchMiddleware implements MiddlewareInterface
{
    /**
     * @var GoogleSearch
     */
    private $search;

    /**
     * GoogleSearchMiddleware constructor.
     *
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->search = new GoogleSearch($config);
    }

    /**
     * @param Message $message
     *
     * @return Message
     */
    public function handle(Message $message)
    {
        $query = $this->getGoogleQuery($message);

        if ($query) {
            $search = '';

            try {
                $search = $this->search->searchGetMessage($query);
            } catch (\Throwable $e) {
            }

            $hasMentions = count($message->mentions);
            $mention = null;

            if ($hasMentions) {
                $mention = $message->mentions[0]->login === \Auth::user()->login
                    ? $message->user
                    : $message->mentions[0];
            }

            if (count($message->mentions)) {
                return $message->answer(
                    trans('google.personal', [
                        'user' => $mention->login,
                        'query' => urlencode($query),
                    ]) .
                    PHP_EOL . $search
                );
            }

            return $message->answer(
                trans('google.common', ['query' => urlencode($query)]) .
                    PHP_EOL . $search
            );
        }

        return $message;
    }

    /**
     * @param Message $message
     *
     * @return string
     */
    private function getGoogleQuery(Message $message) : string
    {
        $words = (new Collection(trans('google.queries')))->map('preg_quote')->implode('|');
        $pattern = sprintf('/^(?:@.*?\s)?(?:%s)\s(.*?)$/isu', $words);
        $found = preg_match($pattern, $message->text_without_special_chars, $matches);
        if ($found) {
            return trim($matches[1]);
        }

        return '';
    }
}
