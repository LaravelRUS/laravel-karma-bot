<?php
namespace Domains\Bot\Middlewares;

use AlgoliaSearch\Client;
use Domains\Message;
use Illuminate\Support\Collection;
use Interfaces\Gitter\Middleware\MiddlewareGroupableInterface;
use Interfaces\Gitter\Middleware\MiddlewareInterface;

/**
 * Class LaravelDocumentationSearcherMiddleware
 */
class LaravelDocumentationSearcherMiddleware implements MiddlewareInterface, MiddlewareGroupableInterface
{
    /**
     * @param Message $message
     * @return mixed
     */
    public function handle(Message $message)
    {
        if (preg_match('/^(@.*?\s)?(?:показать документацию по)\s\`([a-zA-Z ]+)\`?$/isu', $message->text, $matches)) {
            if (!trim($matches[2])) {
                return $message;
            }

            $client = new Client('8BB87I11DE', '8e1d446d61fce359f69cd7c8b86a50de');
            $result = $client->initIndex('docs')->search($matches[2]);

            if (! isset($result['hits'])) {
                $message->answer('По вашему запросу ничего не найдено');
                return null;
            }

            $response = '';

            $hits = new Collection($result['hits']);

            $hits->unique(function($row) {
                return $row['h1'];
            })->map(function($row) {
                $row['link'] = 'https://laravel.com/docs/5.2/'.$row['link'];
                return $row;
            })->take(3)->each(function($row) use(&$response) {
                $response .= " - [{$row['h1']}]({$row['link']})".PHP_EOL;
            });

            $message->answer($response);

            return null;
        }

        return $message;
    }

    /**
     * @return array
     */
    public function getGroup()
    {
        return ['laravel', 'global'];
    }
}
