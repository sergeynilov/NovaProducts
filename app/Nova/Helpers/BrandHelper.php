<?php

namespace App\Nova\Helpers;

class BrandHelper
{
    public function getDefaultImage(): string
    {
        return 'brands/default-brand.png';
    }




    public function getUploadDirectory(): string
    {
        return 'public/brands';
    }


    public function getImageResizeWidth():int
    {
        return 256;
    }

    public function getImageCroppableRatio(): float
    {
        return 16 / 9;
    }

    public function getImageQuality(): int
    {
        return 100;
    }





}
