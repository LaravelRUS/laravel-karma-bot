<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit69dabd85cba3389ca052940510763b25
{
    public static $prefixLengthsPsr4 = array (
        'K' => 
        array (
            'KarmaBot\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'KarmaBot\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'KarmaBot\\Bot\\Connection' => __DIR__ . '/../..' . '/app/Bot/Connection.php',
        'KarmaBot\\Console\\Commands\\BotChannelAdd' => __DIR__ . '/../..' . '/app/Console/Commands/BotChannelAdd.php',
        'KarmaBot\\Console\\Commands\\BotStart' => __DIR__ . '/../..' . '/app/Console/Commands/BotStart.php',
        'KarmaBot\\Console\\Commands\\Watcher' => __DIR__ . '/../..' . '/app/Console/Commands/Watcher.php',
        'KarmaBot\\Console\\Kernel' => __DIR__ . '/../..' . '/app/Console/Kernel.php',
        'KarmaBot\\Exceptions\\Handler' => __DIR__ . '/../..' . '/app/Exceptions/Handler.php',
        'KarmaBot\\Model\\Achieve' => __DIR__ . '/../..' . '/app/Model/Achieve.php',
        'KarmaBot\\Model\\Channel' => __DIR__ . '/../..' . '/app/Model/Channel.php',
        'KarmaBot\\Model\\Karma' => __DIR__ . '/../..' . '/app/Model/Karma.php',
        'KarmaBot\\Model\\System' => __DIR__ . '/../..' . '/app/Model/System.php',
        'KarmaBot\\Model\\User' => __DIR__ . '/../..' . '/app/Model/User.php',
        'KarmaBot\\Providers\\AppServiceProvider' => __DIR__ . '/../..' . '/app/Providers/AppServiceProvider.php',
        'KarmaBot\\Providers\\AuthServiceProvider' => __DIR__ . '/../..' . '/app/Providers/AuthServiceProvider.php',
        'KarmaBot\\Providers\\BotServiceProvider' => __DIR__ . '/../..' . '/app/Providers/BotServiceProvider.php',
        'KarmaBot\\Providers\\EventServiceProvider' => __DIR__ . '/../..' . '/app/Providers/EventServiceProvider.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit69dabd85cba3389ca052940510763b25::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit69dabd85cba3389ca052940510763b25::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit69dabd85cba3389ca052940510763b25::$classMap;

        }, null, ClassLoader::class);
    }
}
