<?php
namespace App\Domains\Karma;

use App\Domains\User\User;
use Illuminate\Database\Eloquent\Model;
use App\Support\JsSerializableTimestampsTrait;

/**
 * Class Karma
 * @package Api\Domains\Karma
 */
class Karma extends Model
{
    use JsSerializableTimestampsTrait;

    /**
     * @var string
     */
    protected $table = 'karma';

    /**
     * @var array
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function target()
    {
        return $this->belongsTo(User::class, 'user_target_id', 'id');
    }
}