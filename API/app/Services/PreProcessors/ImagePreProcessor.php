<?php

namespace App\Services\PreProcessors;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImagePreProcessor implements PreProcessorInterface
{
    private $file;
    private $manager;
    private $pathName;
    const MAX_HEIGHT = 1024; // Make this configurable
    const MAX_WIDTH = 1024; // Make this configurable

    public function __construct(UploadedFile $file)
    {
        $this->manager = new ImageManager(
            new Driver()
        );
        $this->file = $file;
    }


    public function process()
    {
        return $this->resize()
            ->compress()
            ->file;
    }


    private function resize()
    {
        // TODO
        // config check if we need to resize
        // if no then return the file without resizing

        // if yes then resize the image
        $image = $this->manager->read($this->file->getRealPath());
        $width = $image->size()->width();
        $height = $image->size()->height();

        if ($width < self::MAX_WIDTH && $height < self::MAX_HEIGHT) {
            return $this;
        }

        if ($width > self::MAX_WIDTH) {
            $width = self::MAX_WIDTH;
        }

        if ($height > self::MAX_HEIGHT) {
            $height = self::MAX_HEIGHT;
        }

        $image->resize($width, $height);

        // write to tmp file
        $image->save($this->file->getRealPath());
        return $this;
    }

    private function compress()
    {
        // TODO
        // config check if we need to compress
        // if no then return the file without compressing
        // if yes then compress the image
        return $this;
    }
}
