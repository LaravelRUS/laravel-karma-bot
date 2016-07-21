<?php
namespace Domains\Bot\Middlewares;

use Domains\Message;
use BigShark\SQLToBuilder\BuilderClass;
use Domains\Middleware\MiddlewareGroupableInterface;
use Domains\Middleware\MiddlewareInterface;

/**
 * Class SqlBuilderMiddleware
 */
class SqlBuilderMiddleware implements MiddlewareInterface, MiddlewareGroupableInterface
{
    /**
     * @param Message $message
     * @return mixed
     */
    public function handle(Message $message)
    {
        $text = $message->escaped_text;

        if (preg_match('/^(?:select|update|delete|drop|insert)/iu', $text)) {
            try {
                $builder = new BuilderClass($text);
                $message->code($builder->convert(), 'php');

                return null;

            } catch (\Exception $e) {
                $message->pre('SQL Builder error: ' . $e->getMessage());
            }
        }

        return $message;
    }

    /**
     * @return string|array
     */
    public function getGroup()
    {
        return ['testing'];
    }
}
