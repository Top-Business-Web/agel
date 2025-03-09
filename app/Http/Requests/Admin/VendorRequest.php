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
            'phone' => 'required|numeric|digits:11',
            'national_id' => 'required|numeric|digits:14|unique:vendors,national_id',
            'city_id'=>'required|exists:cities,id',
            'has_parent'=> 'boolean|required',
            'parent_id' => 'nullable|exists:vendors,id|required_if:has_parent,1',
            'password' => 'required|min:6|confirmed',
            'image' => 'nullable|image',
//            'module_id' => 'required|exists:modules,id',
        ];
    }

    protected function update(): array
    {
        return [
            'name' => 'nullable',
            'email' => 'nullable|email',
            'phone' => 'nullable|numeric|digits:11',
            'national_id' => 'nullable|numeric|digits:14|unique:vendors,national_id,' . $this->id,
            'city_id'=>'required|exists:cities,id',
            'has_parent'=> 'boolean|required',
            'parent_id' => 'nullable|exists:vendors,id|required_if:has_parent,1',
            'password' => 'nullable|min:6|confirmed',
            'image' => 'nullable|image',
//            'module_id' => 'nullable',
        ];
    }
}
