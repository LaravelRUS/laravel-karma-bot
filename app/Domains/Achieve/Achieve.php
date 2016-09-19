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
    protected $appends = ['title', 'description', 'image'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}