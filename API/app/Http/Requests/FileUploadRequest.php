<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class FileUploadRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'file' => 'required|file|image' // We can remove the image rule if we want to support other file types
        ];
        $this->getDynamicRulesBasedOnMimeType($this->file->getMimeType());

        $rules['file'] = $rules['file'] . $this->getDynamicRulesBasedOnMimeType($this->file->getMimeType());


        return $rules;
    }

    private function getDynamicRulesBasedOnMimeType($mimeType): string
    {
      if(Str::startsWith($mimeType, 'image/')){
        return '| mimes:png,jpg,jpeg';
      }
      // Ability to support other file types here
      return '';
    }
}
