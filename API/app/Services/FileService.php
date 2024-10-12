<?php

namespace App\Services;

use App\Enums\FileType;
use App\Models\File;
use App\Services\PreProcessors\ImagePreProcessor;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    public function __construct()
    {
        // TODO move this to a middleware
        match (config('app.env')) {
            'production' => Config::set('filesystems.default', 'azure'),
            default => Config::set('filesystems.default', 'public'),
        };
    }

    public function upload($file): File
    {
        try {
            $type = $this->getFileType($file);

            // Prerocess the file
            $preprocessor = $this->makePreprocessor($type, $file);
            $file = $preprocessor->process();

            // Upload to storage
            $path = Storage::putFile($type->value, $file);

            // Save to database
            return File::create([
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'public_url' => Storage::url($path),
                'type' => $type->value,
                'mime_type' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension(),
                'size' => $file->getSize()
            ]);
        } catch (Exception $exception) {
            throw new Exception('Unable to upload file: ' . $exception->getMessage());
        }
    }


    private function makePreprocessor(FileType $type, $file)
    {
        return match ($type) {
            FileType::IMAGE => new ImagePreProcessor($file),
        };
    }



    private function getFileType($file): FileType
    {

        if (Str::startsWith($file->getMimeType(), 'image/')) {
            return FileType::IMAGE;
        }
        // Ability to support other file types here
        return FileType::DOCUMENT;
    }
}
