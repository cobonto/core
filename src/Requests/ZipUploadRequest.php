<?php

namespace Cobonto\Requests;

use App\Http\Requests\Request;

class ZipUploadRequest extends Request
{
    //
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
           'module' =>   'required|mimes:zip|max:2000'
        ];
    }
    public function messages()
    {
        return [
            'module.required' => 'You have to choose file for upload',
            'module.mimes' => 'You have to upload zip file',
            'module.mimes' => 'You have to upload zip file',
        ];
    }
}
