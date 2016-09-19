<?php
namespace App\Support;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class JsSerializableTimestampsTrait
 * @package App\Support
 */
trait JsSerializableTimestampsTrait
{
    /**
     * @param $time
     * @return Carbon
     */
    public function getCreatedAtAttribute($time)
    {
        return $this->toJsCompatibleData($time);
    }

    /**
     * @param $time
     * @return Carbon
     */
    public function getUpdatedAtAttribute($time)
    {
        return $this->toJsCompatibleData($time);
    }

    /**
     * @param $time
     * @return Carbon
     */
    protected function toJsCompatibleData($time)
    {
        return new class($time) extends Carbon implements Arrayable
        {
            public function toArray()
            {
                return [
                    'date'     => $this->toIso8601String(),
                    'timezone' => $this->timezone,
                ];
            }
        };
    }
}