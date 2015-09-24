<?php
namespace App\Gitter\Models;

use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Model
 * @package App\Gitter\Models
 */
abstract class Model implements Arrayable
{
    /**
     * @var string
     */
    protected static $primaryKey = 'id';

    /**
     * @var array
     */
    protected static $storage = [];

    /**
     * @var array
     */
    protected static $booted = [];

    /**
     * @param $data
     */
    public static function findOrCreate($data)
    {
        static::boot();

        $id = $data[static::$primaryKey];
        if (!array_key_exists($id, static::$storage[static::class])) {
            static::$storage[static::class][$id] = new static($data);
        }

        return static::$storage[static::class][$id];
    }

    /**
     * @return void
     */
    public static function boot()
    {
        if (array_key_exists(static::class, static::$booted)) {
            return;
        }

        if (array_key_exists(static::class, static::$storage)) {
            static::$storage[static::class] = [];
        }
        static::$booted[static::class] = true;
    }

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {

        $attributes = $this->format($attributes);

        foreach ($attributes as $key => $value) {
            $this->$key = $value;
        }

        static::boot();
        $id = $attributes[static::$primaryKey];
        static::$storage[static::class][$id] = $this;
    }

    /**
     * @param array $attributes
     * @return array
     */
    public function format(array $attributes)
    {
        return $attributes;
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $setter = sprintf('set%sAttribute', Str::studly($key));
        if (method_exists($this, $setter)) {
            $value = call_user_func([$this, $setter], $value);
        }

        $this->attributes[$key] = $value;
    }

    /**
     * @param $key
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function __get($key)
    {
        $value = null;
        if (array_key_exists($key, $this->attributes)) {
            $value = $this->attributes[$key];
        }

        $getter = sprintf('get%sAttribute', Str::studly($key));
        if (method_exists($this, $getter)) {
            return call_user_func([$this, $getter], $value);
        }

        return $value;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        return $this->toArray();
    }
}