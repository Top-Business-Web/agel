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
            Unsurpassed::create([
                'name' => $row['name'] ?? $row['full_name'] ?? null, // Handle different column names
                'phone' => $row['phone'] ?? $row['mobile'] ?? null,
                'national_id' => $row['national_id'] ?? $row['id_number'] ?? null,
                'office_name' => $row['office_name'] ?? null,
                'office_phone' => $row['office_phone'] ?? null,
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
            'name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^\+966[0-9]{9}$/|unique:unsurpasseds,phone|unique:unsurpasseds,office_phone',
            'national_id' => 'required|numeric|digits:10|unique:unsurpasseds,national_id',
            'office_name' => 'nullable|string|max:255',
            'office_phone' => 'nullable|string|regex:/^\+966[0-9]{9}$/|unique:unsurpasseds,office_phone|unique:unsurpasseds,phone',
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
