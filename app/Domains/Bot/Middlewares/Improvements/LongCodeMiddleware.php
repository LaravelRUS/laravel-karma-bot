<?php
/**
 * This file is part of GitterBot package.
 *
 * @author atehnix <http://vk.com/atehnix>
 * @date 11.04.2016 17:45
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Bot\Middlewares\Improvements;

use Domains\Bot\Middlewares\Middleware;
use Domains\Message\Message;
use Domains\Message\Text;
use GrahamCampbell\GitHub\GitHubManager;

/**
 * Class LongCodeMiddleware
 */
class LongCodeMiddleware implements Middleware
{
    const MAX_CODE_LINES = 2;

    /**
     * @param Message $message
     * @param GitHubManager $manager
     * @return mixed
     */
    public function handle(Message $message, GitHubManager $manager)
    {
        $codeInserts = $this->getCodeDeclaration($message);

        if (count($codeInserts)) {
            $codeFullLines = 0;

            foreach ($codeInserts as $match) {
                list($full, $lang, $code) = $match;

                $codeFullLines += (new Text($code))->linesCount();
            }

            if ($codeFullLines >= static::MAX_CODE_LINES) {
                try {
                    $response = $this->exportToGist($manager, $message, $codeInserts);

                    return trans('long.gist', [
                        'user'     => $message->user->credinals->login,
                        'gist_url' => $response['html_url'],
                    ]);
                } catch (\Throwable $e) {
                    trans('long.code', [
                        'user' => $message->user->credinals->login,
                    ]);
                }
            }
        }
    }

    /**
     * @param Message $message
     * @return array
     */
    protected function getCodeDeclaration(Message $message)
    {
        $pattern = '/(?:`{3})([a-z]*)\n(.*?)\n(?:`{3})/ismu';
        preg_match_all($pattern, $message->text->toString(), $matches, PREG_SET_ORDER);

        return $matches;
    }

    /**
     * @param GitHubManager $manager
     * @param Message $message
     * @param array $codeInserts
     * @return \Guzzle\Http\EntityBodyInterface|mixed|string
     * @throws \Github\Exception\MissingArgumentException
     */
    private function exportToGist(GitHubManager $manager, Message $message, array $codeInserts)
    {
        $data = [
            'files'       => [],
            'public'      => false,
            'description' => sprintf(
                'This gist was be generated special for @%s. Enjoy ;)',
                $message->user->credinals->login
            ),
        ];

        foreach ($codeInserts as $i => $match) {
            list($full, $lang, $code) = $match;

            $name = 'file-' . ($i + 1) . '.' . ($lang ? mb_strtolower($lang) : 'txt');

            $data['files'][$name] = [
                'content' => $code,
            ];
        }

        return $manager->gist()->create($data);
    }
}
