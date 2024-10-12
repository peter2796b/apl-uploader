<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'path' => $this->path,
            'public_url' => $this->public_url,
            'type' => $this->type,
            'mime_type' => $this->mime_type,
            'extension' => $this->extension,
            'size' => $this->size
        ];
    }
}
