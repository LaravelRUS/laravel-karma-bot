<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 09.10.2015 16:56
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Interfaces\Gitter;

use Converter\BBCodeConverter;
use Domains\Bot\TextParserInterface;

class TextParser extends BBCodeConverter implements TextParserInterface
{

    /**
     * @param string $text
     *
     * @return string
     */
    public static function escape($text)
    {
        $text = preg_replace('/(?P<char>(?:_|\*))(.+?)(?P=char)/isu', '\\\$1$2\\\$1', $text);
        $text = preg_replace('/\*\*(.+?)\*\*/isu', '*\\*$1*\\*', $text);
        $text = preg_replace('/\-\-(\-)+/isu', '\-\-\-', $text);
        $text = preg_replace('/\n*^(?!\w\s+)(#)/isu', '\\#', $text);
        $text = preg_replace('/\[(.*?)\]\((.*?)\)/isu', '\\[$1\\]\\($2\\)', $text);

        return $text;
    }

    public function __construct() {}

    /**
     * @brief replace BBCode user.
     */
    protected function removeUser() {

        $this->text = preg_replace_callback('%\[user\]([\W\D\w\s]*?)\[/user\]%iu',

            function ($matches) {
                $username = trim($matches[1]);

                return empty($username) ? "" : "@{$username}";
            },

            $this->text);

    }

    /**
     * @brief replace BBCode pre.
     */
    protected function removePre() {

        $this->text = preg_replace_callback('%\[pre\]([\W\D\w\s]*?)\[/pre\]%iu',

            function ($matches) {
                return "`{$matches[1]}`";
            },

            $this->text);

    }

    /**
     * @brief replace BBCode header.
     */
    protected function removeHeader() {

        $this->text = preg_replace_callback('%\[h([0-6]{1})\]([\W\D\w\s]*?)\[/h[0-6]?\]%iu',

            function ($matches) {
                $size = $matches[1];

                return str_repeat('#', $size).' '.$matches[2].PHP_EOL;
            },

            $this->text);

    }

    public function toMarkdown()
    {
        parent::toMarkdown();
        $this->removeHeader();
        $this->removePre();
        $this->removeUser();

        return $this->text;
    }

    /**
     * @param string $message
     *
     * @return string
     */
    public function parse(string $message)
    {
        $this->text = $message;

        return $this->toMarkdown();
    }
}