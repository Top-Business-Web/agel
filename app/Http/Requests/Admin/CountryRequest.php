<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
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
            'name.ar' => 'required|unique:countries,name->ar',
            'name.en' => 'required|unique:countries,name->en',
            'location' => 'required',
        ];
    }

    protected function update(): array
    {
        return [
            'name.ar' => 'nullable|unique:countries,name->ar,'.$this->country,
            'name.en' => 'nullable|unique:countries,name->en,'.$this->country,
            'location' => 'nullable',
        ];
    }
}
