<?php

namespace App\Facades;

use Intervention\Image\ImageManager;

class Image
{
    protected static $manager;

    public static function getManager()
    {
        if (!self::$manager) {
            self::$manager = ImageManager::gd();
        }
        return self::$manager;
    }

    public static function make($data)
    {
        return self::getManager()->read($data);
    }

    public static function gd(...$options)
    {
        return ImageManager::gd(...$options);
    }

    public static function imagick(...$options)
    {
        return ImageManager::imagick(...$options);
    }

    public static function canvas($width, $height, $background = null)
    {
        return self::getManager()->create($width, $height);
    }
}