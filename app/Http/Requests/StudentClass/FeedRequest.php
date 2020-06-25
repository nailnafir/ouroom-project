<?php

namespace App\Http\Requests\StudentClass;

use Illuminate\Foundation\Http\FormRequest;

class FeedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
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
            'judul'      => 'required|min:2',
            'kategori'   => 'required',
            'detail'     => 'string',
            'file'       => 'string',
            'deadline'   => 'date',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'judul.required' => 'Judul tidak boleh dikosongkan',
            'kategori.required' => 'Kategori tidak boleh dikosongkan',         
        ];
    }
}
