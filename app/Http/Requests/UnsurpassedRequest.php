<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnsurpassedRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('phone')) {
            $this->merge([
                'phone' => '+966' . preg_replace('/^0+/', '', $this->phone)
            ]);
        }


        if ($this->has('office_phone')) {
            $this->merge([
                'office_phone' => '+966' . preg_replace('/^0+/', '', $this->office_phone)
            ]);
        }
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
            'phone' => 'required|unique:unsurpasseds,phone,'.$this->id,
            'national_id' => 'required|numeric|digits:10|unique:unsurpasseds,national_id,' . $this->route('unsurpassed'),
            'office_name' => 'nullable|string|max:255',
            'office_phone' => 'nullable|unique:unsurpasseds,office_phone,'.$this->id,
        ];
    }

    public function messages()
    {
        return [
            'phone.regex' => 'يجب أن يبدأ رقم الهاتف بـ +966 ويحتوي على 9 أرقام فقط.',
            'office_phone.regex' => ' يجب أن يبدأ رقم الهاتف بـ +966 ويحتوي على 9 أرقام فقط.',
        ];
    }
}
