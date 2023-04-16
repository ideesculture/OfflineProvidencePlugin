<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit605c70032e260abec8a89da6540fd82c
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Swaggest\\JsonDiff\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Swaggest\\JsonDiff\\' => 
        array (
            0 => __DIR__ . '/..' . '/swaggest/json-diff/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit605c70032e260abec8a89da6540fd82c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit605c70032e260abec8a89da6540fd82c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit605c70032e260abec8a89da6540fd82c::$classMap;

        }, null, ClassLoader::class);
    }
}