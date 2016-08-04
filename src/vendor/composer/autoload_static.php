<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit584654709bd97503e282b4bde4207c71
{
    public static $prefixLengthsPsr4 = array (
        'e' => 
        array (
            'events\\' => 7,
        ),
        'c' => 
        array (
            'classes\\' => 8,
        ),
        'P' => 
        array (
            'PAMI\\' => 5,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'events\\' => 
        array (
            0 => __DIR__ . '/../..' . '/events',
        ),
        'classes\\' => 
        array (
            0 => __DIR__ . '/../..' . '/classes',
        ),
        'PAMI\\' => 
        array (
            0 => __DIR__ . '/..' . '/marcelog/pami/src/PAMI',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 
            array (
                0 => __DIR__ . '/..' . '/psr/log',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit584654709bd97503e282b4bde4207c71::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit584654709bd97503e282b4bde4207c71::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit584654709bd97503e282b4bde4207c71::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
