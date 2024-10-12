<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use App\Http\Resources\FileResource;
use App\Services\FileService;

class FileController extends Controller
{
    private FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function upload(FileUploadRequest $request)
    {
        $file = $this->fileService->upload($request->file('file'));
        return new FileResource($file);
    }
}
