<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'phone'=>   ['required', Rule::unique('vendors', 'phone')->where(function ($query) {
                return $query->where('phone', '+966' . $this->phone);
            })],
            'city_id' => 'required|exists:cities,id',
            'national_id' => 'required|numeric|unique:vendors,national_id|digits:10',
            'password' => 'required|min:6|confirmed',
            'image' => 'nullable|image',
            "permissions"=>'required|array',
            'commercial_number' => 'required|digits:10|numeric|unique:vendors,commercial_number',
        ];
    }

    protected function update(): array
    {
        return [

            'phone'=>   ['required', Rule::unique('vendors', 'phone')->where(function ($query) {
                return $query->where('phone', '+966' . $this->phone);
            })->ignore($this->id)],


          'id' => 'required|exists:vendors,id',
            'name' => 'nullable',
            'email' => 'nullable|email|unique:vendors,email,' . $this->id,
            'national_id' => 'nullable|numeric|digits:10|unique:vendors,national_id,' . $this->id,
            'city_id' => 'required|exists:cities,id',
            'password' => 'nullable|min:6|confirmed',
            'image' => 'nullable|image',
            "permissions"=>'required|array',
            'commercial_number' => 'nullable|numeric|unique:vendors,commercial_number,' . $this->id,

        ];
    }
}
