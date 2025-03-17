<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->isMethod('put')) {
            return $this->update();
        } else {
            return $this->store();
        }
    }

    protected function store(): array
    {
        return [

            'name' => 'required',
            'email' => 'required|email|unique:vendors,email',
            'phone' => 'required|numeric|unique:vendors,phone|digits:9',
            'region_id'=>'required|exists:regions,id',
            'national_id' => 'required|numeric|unique:vendors,national_id|digits:10',
            'password' => 'required|min:6|confirmed',
            'image' => 'nullable|image',
            "permissions"=>'required|array'
        ];
    }

    protected function update(): array
    {
        return [
            'name' => 'nullable',
            'email' => 'nullable|email|unique:vendors,email,' . $this->id,
            'phone' => 'nullable|numeric|digits:9|unique:vendors,phone,' . $this->id,
            'national_id' => 'nullable|numeric|digits:10|unique:vendors,national_id,' . $this->id,
            'region_id'=>'required|exists:regions,id',
            'password' => 'nullable|min:6|confirmed',
            'image' => 'nullable|image',
            "permissions"=>'required|array'

        ];
    }
}
