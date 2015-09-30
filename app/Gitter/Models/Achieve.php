<?php
namespace App\Gitter\Models;

/**
 * Class Achieve
 * @package App\Gitter\Models
 *
 * @property string $title
 * @property string $description
 * @property string $image
 * @property \App\User $user
 */
class Achieve extends Model
{
    // http://docs.rudev.org/stream/a265faa4be6dbd24f957db97b89c4e51
    public function __toString()
    {
        $strings = [
            sprintf('> # [%s](%s)', $this->title, $this->image),
            sprintf('> *%s*', $this->description),
            sprintf('> *Achievement unlocked for @%s*', $this->user->login),
            sprintf('> ![%s](%s)', $this->title, $this->image)
        ];

        return implode("\n", $strings);
    }
}