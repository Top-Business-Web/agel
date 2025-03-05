<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
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
            'name.ar' => 'required|unique:cities,name->ar',
            'name.en' => 'required|unique:cities,name->en',
            'country_id' => 'required',
            'location' => 'required',
        ];
    }

    protected function update(): array
    {
        return [
            'name.ar' => 'nullable|unique:cities,name->ar,' . $this->city,
            'name.en' => 'nullable|unique:cities,name->en,' . $this->city,
            'country_id' => 'nullable',
            'location' => 'nullable',
        ];
    }


    public function messages()
    {
        return [
            'name[ar].required' => trans('name city required ar'),
            'name[en].required' => trans('name city required en'),
            'name[ar].unique' => trans('name city must be unique'),
            'name[en].unique' => trans('name city must be unique'),
            'country_id.required' => trans('name country required'),
            'location.required' => trans('name location required'),
        ];
    }
}
