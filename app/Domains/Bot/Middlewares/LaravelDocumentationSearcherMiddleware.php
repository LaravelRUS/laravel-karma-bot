<?php
namespace Domains\Bot\Middlewares;

use AlgoliaSearch\Client;
use Domains\Message;
use Domains\Middleware\MiddlewareInterface;
use Illuminate\Support\Collection;

/**
 * Class LaravelDocumentationSearcherMiddleware
 */
class LaravelDocumentationSearcherMiddleware implements MiddlewareInterface
{
    /**
     * @param Message $message
     * @return mixed
     */
    public function handle(Message $message)
    {
        $pattern = '/^(@?.*?\s?)(информация|доки|документация|larvel doc)\s+(?:про|по)?\s*(.*?)$/isu';
        if (preg_match($pattern, $message->text_without_special_chars, $matches)) {

            if (!trim($query = $matches[3])) {
                return $message;
            }

            $client = new Client(
                '8BB87I11DE',
                '8e1d446d61fce359f69cd7c8b86a50de'
            );

            $result = $client->initIndex('docs')->search($query);

            if ((int) array_get($result, 'nbHits') === 0) {
                $message->text = "погугли {$query}";
                return app(NewGoogleSearchMiddleware::class)->handle($message);
            }

            $response = '';

            $hits = new Collection($result['hits']);

            $hits->unique(function ($row) {
                return $row['h1'];
            })->map(function ($row) {
                $row['link'] = 'https://laravel.com/docs/5.2/' . $row['link'];
                return $row;
            })->take(3)->each(function ($row) use (&$response) {
                $title = '';
                foreach (['h1', 'h2', 'h3', 'h4', 'h5'] as $tag) {
                    if (isset($row[$tag])) {
                        $title .= ' ' . $row[$tag];
                    }
                }

                $response .= "[*] [i][url={$row['link']}]{$title}[/url][/i]" . PHP_EOL;
            });

            if (!empty($response)) {
                $message->answer(trans('search.results', [
                    'results' => '[list]' . PHP_EOL . $response . PHP_EOL . '[/list]',
                ]));
            }


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
