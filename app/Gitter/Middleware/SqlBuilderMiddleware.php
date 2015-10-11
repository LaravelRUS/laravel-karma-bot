<?php
namespace App\Gitter\Middleware;

use App\Message;
use App\Gitter\Client;
use App\Gitter\Karma\Validator;
use BigShark\SQLToBuilder\BuilderClass;

/**
 * Class SqlBuilderMiddleware
 * @package App\Gitter\Middleware
 */
class SqlBuilderMiddleware implements MiddlewareInterface
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

            } catch (\Exception $e) {
                $message->pre('SQL Builder error: ' . $e->getMessage());
            }
        }

        return $message;
    }
}
