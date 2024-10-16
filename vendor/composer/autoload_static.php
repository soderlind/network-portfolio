<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0eaa8868951dd8982309e1f35ef4a1e5
{
    public static $prefixLengthsPsr4 = array (
        'N' => 
        array (
            'NetworkPortfolio\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'NetworkPortfolio\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0eaa8868951dd8982309e1f35ef4a1e5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0eaa8868951dd8982309e1f35ef4a1e5::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0eaa8868951dd8982309e1f35ef4a1e5::$classMap;

        }, null, ClassLoader::class);
    }
}
