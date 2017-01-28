<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Model{
/**
 * Class Achieve
 *
 * @package KarmaBot\Model
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $image
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\User[] $users
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Achieve whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Achieve whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Achieve whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Achieve whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Achieve whereTitle($value)
 */
	class Achieve extends \Eloquent {}
}

namespace App\Model{
/**
 * Class Channel
 *
 * @package KarmaBot\Model
 * @property int $id
 * @property int $system_id
 * @property string $sys_channel_id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Model\System $system
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Channel inSystem(\App\Model\System $system)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Channel whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Channel whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Channel whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Channel whereSysChannelId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Channel whereSystemId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Channel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Channel withExternalId($id)
 */
	class Channel extends \Eloquent {}
}

namespace App\Model{
/**
 * Class Karma
 *
 * @package KarmaBot\Model
 * @property int $id
 * @property int $system_id
 * @property int $channel_id
 * @property int $from_user_id
 * @property int $to_user_id
 * @property string $sys_message_id
 * @property string $created_at
 * @property string $updated_at
 * @property-read \App\Model\User $target
 * @property-read \App\Model\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Karma whereChannelId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Karma whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Karma whereFromUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Karma whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Karma whereSysMessageId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Karma whereSystemId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Karma whereToUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Karma whereUpdatedAt($value)
 */
	class Karma extends \Eloquent {}
}

namespace App\Model{
/**
 * Class Message
 *
 * @package KarmaBot\Model
 * @property int $id
 * @property string $sys_message_id
 * @property int $channel_id
 * @property int $user_id
 * @property string $body
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Message whereBody($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Message whereChannelId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Message whereExternalMessage(\Serafim\KarmaCore\Io\ReceivedMessageInterface $message, \App\Model\Channel $channel, \App\Model\User $user)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Message whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Message whereSysMessageId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Message whereUserId($value)
 */
	class Message extends \Eloquent {}
}

namespace App\Model{
/**
 * Class System
 *
 * @package KarmaBot\Model
 * @property string $driver_class
 * @property int $id
 * @property string $title
 * @property string $name
 * @property string $driver
 * @property string $token
 * @property string $icon
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Channel[] $channels
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\User[] $users
 * @method static \Illuminate\Database\Query\Builder|\App\Model\System whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\System whereDriver($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\System whereIcon($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\System whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\System whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\System whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\System whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\System whereUpdatedAt($value)
 */
	class System extends \Eloquent {}
}

namespace App\Model{
/**
 * Class User
 *
 * @package KarmaBot\Model
 * @property int $id
 * @property string $name
 * @property string $avatar
 * @property string $url
 * @property string $login
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Achieve[] $achievements
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Karma[] $karma
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\System[] $systems
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Karma[] $thanks
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User whereAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User whereExternalUser(\App\Model\System $system, \Serafim\KarmaCore\Io\UserInterface $user)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User whereLogin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\User whereUrl($value)
 */
	class User extends \Eloquent {}
}

