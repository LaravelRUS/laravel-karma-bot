<?php
namespace App\Gitter\Http;

/**
 * Class UrlStorage
 * @package App\Gitter\Http
 */
class UrlStorage
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var array
     */
    protected $routes = [
        'messages' => 'https://stream.gitter.im/v1/rooms/{roomId}/chatMessages?access_token={token}'
    ];

    /**
     * @param string $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * @param $name
     * @param array $args
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function route($name, array $args = [])
    {
        if (!array_key_exists($name, $this->routes)) {
            $message = sprintf('%s route not found.', $name);
            throw new \InvalidArgumentException($message);
        }

        $url = str_replace('{token}', $this->token, $this->routes[$name]);
        foreach ($args as $key => $value) {
            $url = str_replace(sprintf('{%s}', $key), $value, $url);
        }
        return $url;
    }
}