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

namespace Interfaces\Slack;

class TextParser extends \Interfaces\Gitter\TextParser
{

    /**
     * @brief Replaces BBCode urls.
     */
    protected function replaceUrls() {

        $this->text = preg_replace_callback('%\[url\s*=\s*("(?:[^"]*")|\A[^\']*\Z|(?:[^\'">\]\s]+))\s*(?:[^]\s]*)\]([\W\D\w\s]*?)\[/url\]%iu',

            function ($matches) {
                if (isset($matches[1]) && isset($matches[2]))
                    return "<".$matches[1]."|".$matches[2].">";
                else
                    throw new \RuntimeException(sprintf("Text identified by '%d' has malformed BBCode urls", $this->id));
            },

            $this->text
        );

    }
}