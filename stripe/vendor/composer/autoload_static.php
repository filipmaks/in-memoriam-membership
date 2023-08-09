<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit51f1c5f7345e87cbe63de5eca81740f0
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit51f1c5f7345e87cbe63de5eca81740f0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit51f1c5f7345e87cbe63de5eca81740f0::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit51f1c5f7345e87cbe63de5eca81740f0::$classMap;

        }, null, ClassLoader::class);
    }
}
