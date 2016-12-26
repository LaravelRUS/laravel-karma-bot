<?php declare(strict_types = 1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Console\Render;

use Illuminate\Console\OutputStyle;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class Table
 * @package KarmaBot\Console\Render
 */
class Table
{
    /**
     * @var string|null
     */
    private $title;

    /**
     * @var bool
     */
    private $booted = false;

    /**
     * @var OutputStyle
     */
    private $out;

    /**
     * Render constructor.
     * @param OutputStyle $out
     * @param string $title
     */
    public function __construct(OutputStyle $out, string $title = null)
    {
        $this->title = $title;
        $this->out = $out;
    }

    /**
     * @param array $valueAndSize
     * @param array $colors
     */
    public function render(array $valueAndSize, array $colors = [])
    {
        $size = $this->getSizeByColumns($valueAndSize);

        if (!$this->booted) {
            $this->renderHeader($size);
            $this->booted = true;
        }

        $items = (new Collection($valueAndSize))
            ->map(function ($value, $key) use (&$colors) {
                $color = count($colors) > 0 ? array_shift($colors) : 'comment';

                if ($color === null) {
                    return $this->pad(' ' . $key, $value);
                }

                return '<' . $color . '>' . $this->pad(' ' . $key, $value) . '</' . $color . '>';
            })
            ->implode('|');

        $this->out->writeln('|' . $items . '|');
    }

    /**
     * @param array $valueAndSize
     * @return int
     */
    private function getSizeByColumns(array $valueAndSize): int
    {
        return (int)array_sum(array_values($valueAndSize)) - 2 + (count($valueAndSize) * 2);
    }

    /**
     * @param int $size
     * @return $this
     */
    public function renderHeader(int $size)
    {
        if ($this->title !== null) {
            $this->delimiter($size, '⋯');
            $this->out->writeln('| <info>' . $this->pad($this->title, $size - 4, ' ', STR_PAD_BOTH) . '</info> |');
            $this->delimiter($size, '⋯');
        }

        return $this;
    }

    /**
     * @param int $size
     * @param string $char
     * @return $this
     */
    public function delimiter(int $size, $char = '·')
    {
        $this->out->writeln('|' . str_repeat($char, $size - 2) . '|');

        return $this;
    }

    /**
     * @param string $input
     * @param int $padLength
     * @param string $char
     * @param int $type
     * @param string|null $encoding
     * @return string
     */
    private function pad(
        string $input,
        int $padLength,
        string $char = ' ',
        int $type = STR_PAD_RIGHT,
        string $encoding = null
    ) {
        if (Str::length($input) >= $padLength) {
            $input = Str::limit($input, $padLength - 3);
        }

        $diff = $encoding === null
            ? strlen($input) - mb_strlen($input)
            : strlen($input) - mb_strlen($input, $encoding);

        return str_pad($input, $padLength + $diff, $char, $type);
    }
}
