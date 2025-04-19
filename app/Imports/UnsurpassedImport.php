<?php

namespace App\Imports;

use App\Models\Unsurpassed;

// Import your model
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

// For handling header row
use Maatwebsite\Excel\Concerns\WithValidation;

// For data validation
use Illuminate\Validation\Rule;

// For custom validation rules

class UnsurpassedImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * Process the imported Excel data.
     *
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            Unsurpassed::updateOrCreate([
                'name' => $row['name'] ?? null, // Handle different column names
                'phone' => '+966'.$row['phone']  ?? null,
                'national_id' => $row['national_id']  ?? null,
                'office_name' => $row['office_name'] ?? null,
                'office_phone' => '+966'.$row['office_phone'] ?? null,
            ]);
        }
    }

    /**
     * Validation rules for the import.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'phone' => 'required|regex:/^[0-9]{9}$/|unique:unsurpasseds,phone|unique:unsurpasseds,office_phone',
            'national_id' => 'required|numeric|digits:10|unique:unsurpasseds,national_id',
            'office_name' => 'nullable|max:255',
            'office_phone' => 'nullable|regex:/^[0-9]{9}$/|unique:unsurpasseds,office_phone|unique:unsurpasseds,phone',
        ];
    }
    public function messages()
    {
        return [
            'phone.required' => 'The phone field is required.',
            'phone.regex' => 'The phone number must contain exactly 9 digits.',
            'phone.unique' => 'The phone number must be unique and not match any existing phone or office phone.',
            'national_id.required' => 'The national ID field is required.',
            'national_id.numeric' => 'The national ID must be a numeric value.',
            'national_id.digits' => 'The national ID must be exactly 10 digits.',
            'national_id.unique' => 'The national ID already exists in the database.',
            'office_name.max' => 'The office name must not exceed 255 characters.',
            'office_phone.regex' => 'The office phone number must contain exactly 9 digits.',
            'office_phone.unique' => 'The office phone number must be unique and not match any existing office phone or phone.',
        ];
    }

    /**
     * Custom validation messages (optional).
     *
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'national_id.unique' => 'The national ID already exists in the database.',
            'name.required' => 'The name field is required.',
            'phone.regex' => 'The phone number must start with +966 and contain only 9 digits.',
            'office_phone.regex' => 'The office phone number must start with +966 and contain only 9 digits.',
        ];
    }
}
