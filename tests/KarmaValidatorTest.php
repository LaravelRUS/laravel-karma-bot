<?php
namespace Tests;

use App\Gitter\Karma\Validator;
use App\Message;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class KarmaValidatorTest extends AbstractTestCase
{
    protected $validator;
    protected $message;

    public function setUp()
    {
        parent::setUp();

        $this->validator = new Validator();

        $this->message = new Message();
        $this->message->user = new User();
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {

    }
}
