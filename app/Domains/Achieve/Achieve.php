<?php
namespace App\Domains\Achieve;

use App\Domains\User\User;
use Illuminate\Database\Eloquent\Model;
use App\Support\JsSerializableTimestampsTrait;

/**
 * Class Achieve
 * @package Api\Domains\Achieve
 */
class Achieve extends Model
{
    use JsSerializableTimestampsTrait;

    /**
     * @var string
     */
    protected $table = 'achievements';

    /**
     * @var array
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var array
     */
    protected $appends = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievements');
    }
}