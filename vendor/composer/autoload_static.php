<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit34a4ef840f3e29b3ab46f1b34ee7afe2
{
    public static $files = array (
        'decc78cc4436b1292c6c0d151b19445c' => __DIR__ . '/..' . '/phpseclib/phpseclib/phpseclib/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'p' => 
        array (
            'phpseclib\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'phpseclib\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpseclib/phpseclib/phpseclib',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit34a4ef840f3e29b3ab46f1b34ee7afe2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit34a4ef840f3e29b3ab46f1b34ee7afe2::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit34a4ef840f3e29b3ab46f1b34ee7afe2::$classMap;

        }, null, ClassLoader::class);
    }
}
