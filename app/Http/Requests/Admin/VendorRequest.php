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
            'phone' => 'required|numeric',
            'national_id' => 'required|numeric|unique:vendors,national_id',
            'city_id'=>'required|exists:cities,id',
            'password' => 'required|min:6|confirmed',
            'image' => 'nullable|image',
        ];
    }

    protected function update(): array
    {
        return [
            'name' => 'nullable',
            'email' => 'nullable|email',
            'phone' => 'nullable|numeric',
            'national_id' => 'nullable|numeric|unique:vendors,national_id,' . $this->id,
            'city_id'=>'required|exists:cities,id',
            'password' => 'nullable|min:6|confirmed',
            'image' => 'nullable|image',
        ];
    }
}
