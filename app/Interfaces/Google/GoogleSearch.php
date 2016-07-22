<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date   18.04.2016 16:29
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Interfaces\Google;

use Amp\Artax\Client;
use Amp\Artax\Request;
use Amp\Artax\Response;
use Illuminate\Config\Repository;
use function Amp\wait;

/**
 * Class GoogleSearch
 * @package Domains\Bot\Middlewares\Common\GoogleSearch
 */
class GoogleSearch
{
    /**
     * @var Client
     */
    private $artax;

    /**
     * @var string
     */
    private $token;

    /**
     * GoogleSearch constructor.
     *
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->artax = new Client();
        $this->token = $config->get('google.token');
    }

    /**
     * @param string $query
     *
     * @return array
     * @throws \Throwable
     */
    public function search(string $query)
    {
        if (empty($this->token)) {
            return [];
        }

        $url = 'https://www.googleapis.com/customsearch/v1?'.http_build_query([
            'key' => $this->token,
            'lr' => 'lang_ru',
            'cx' => '017648015832347857471:ap7pijkcqh4',
            'q' => $query,
        ]);

        $request = new Request();
        $request->setMethod('GET');
        $request->setUri($url);
        $request->setProtocol('1.1');

        /** @var Response $response */
        $response = wait($this->artax->request($request));

        $result = json_decode($response->getBody());

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Broken google api response');
        }

        return property_exists($result, 'items') ? $result->items : [];
    }

    /**
     * @param string $query
     *
     * @throws \Throwable
     * @return string
     */
    public function searchGetMessage(string $query) : string
    {
        $result = [trans('google.results')];
        $response = $this->search($query);

        foreach ($response as $i => $link) {
            if ($i >= 3) {
                continue;
            }

            $result[] = sprintf('[*] [i][url=%s]%s[/url][/i]', $link->link, $link->title);
        }

        if (!empty($result)) {
            $result = '[list]'.implode(PHP_EOL, $result).'[/list]';
        }

        return $result;
    }
}