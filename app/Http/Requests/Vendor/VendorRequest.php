<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     * This ensures that the phone number is stored with +966.
     */
    protected function prepareForValidation()
    {
        if ($this->has('phone')) {
            $this->merge([
                'phone' => '+966' . preg_replace('/^0+/', '', $this->phone)
            ]);
        }
    }

    /**
     * Determine the validation rules.
     */
    public function rules()
    {
        return $this->isMethod('put') ? $this->update() : $this->store();
    }

    /**
     * Validation rules for store request.
     */
    protected function store(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email',
            'phone' => [
                'required',
                'string',
                'regex:/^\+966\d{9}$/',
                'unique:vendors,phone'
            ],
            'city_id' => 'required|exists:cities,id',
            'national_id' => 'required|numeric|digits:10|unique:vendors,national_id',
            'password' => 'required|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'permissions' => 'required|array',
            'branch_ids' => 'required|array',
        ];
    }

    /**
     * Validation rules for update request.
     */
    protected function update(): array
    {
        return [
            'id' => 'required|exists:vendors,id',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:vendors,email,' . $this->id,
            'phone' => [
                'required',
                'string',
                'regex:/^\+966\d{9}$/',
                'unique:vendors,phone,' . $this->id
            ],
            'national_id' => 'nullable|numeric|digits:10|unique:vendors,national_id,' . $this->id,
            'city_id' => 'required|exists:cities,id',
            'password' => 'nullable|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'permissions' => 'required|array',
            'branch_ids' => 'required|array',
        ];
    }
}
