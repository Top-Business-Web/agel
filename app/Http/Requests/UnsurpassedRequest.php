<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnsurpassedRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^\+966[0-9]{9}$/|unique:unsurpasseds,phone|unique:unsurpasseds,office_phone',
            'national_id' => 'required|numeric|digits:10|unique:unsurpasseds,national_id',
            'office_name' => 'nullable|string|max:255',
            'office_phone' => 'nullable|string|regex:/^\+966[0-9]{9}$/|unique:unsurpasseds,phone|unique:unsurpasseds,office_phone',
        ];
    }

    protected function update(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^\+966[0-9]{9}$/|unique:unsurpasseds,phone|unique:unsurpasseds,office_phone',
            'national_id' => 'required|numeric|digits:10|unique:unsurpasseds,national_id,' . $this->route('unsurpassed'),
            'office_name' => 'nullable|string|max:255',
            'office_phone' => 'nullable|string|regex:/^\+966[0-9]{9}$/|unique:unsurpasseds,phone|unique:unsurpasseds,office_phone',
        ];
    }

    public function messages()
    {
        return [
            'phone.regex' => 'The phone number must start with +966 and contain only 9 digits.',
            'office_phone.regex' => 'The office phone number must start with +966 and contain only 9 digits.',
        ];
    }
}
