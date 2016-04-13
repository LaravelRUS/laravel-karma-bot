<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 13.04.2016 17:13
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Bot\Middlewares\Laravel;


use Domains\Bot\Middlewares\Middleware;
use Domains\Message\Message;
use BigShark\SQLToBuilder\BuilderClass;

/**
 * Class SqlBuilderMiddleware
 * @package Domains\Bot\Middlewares\Laravel
 */
class SqlBuilderMiddleware implements Middleware
{
    /**
     * @param Message $message
     * @return string|void
     */
    public function handle(Message $message)
    {
        $isSql = $this->isSqlQuery($message);

        if ($isSql) {
            try {
                $builder = new BuilderClass($message->text->toString());
                return $builder->convert();

            } catch (\Exception $e) {
                return 'Я не понимать ваш SQL =(';
            }
        }
    }

    /**
     * @param Message $message
     * @return boolean
     */
    private function isSqlQuery(Message $message) : bool
    {
        $text = $message->text->escaped;
        return (bool)preg_match('/^(select)/iu', $text);
    }
}
