<?php
namespace App;

/**
 * Class User
 * @package App
 */
class User extends \Eloquent
{
    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @var array
     */
    protected $fillable = ['gitter_id', 'url', 'login', 'name', 'avatar'];
}
