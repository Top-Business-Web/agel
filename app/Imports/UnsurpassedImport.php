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
            $unsurpassed = Unsurpassed::where('phone', '+966'.$row['phone'])
                ->orWhere('office_phone', '+966'.$row['office_phone'])
                ->orWhere('national_id', $row['national_id'])
                ->first();

            if ($unsurpassed) {
                $unsurpassed->update([
                    'name' => $row['name'] ?? $unsurpassed->name,
                    'office_name' => $row['office_name'] ?? $unsurpassed->office_name,
                ]);
            } else {
                // Create new record
                Unsurpassed::create([
                    'name' => $row['name'] ?? null,
                    'phone' => '+966'.$row['phone'] ?? null,
                    'national_id' => $row['national_id'] ?? null,
                    'office_name' => $row['office_name'] ?? null,
                    'office_phone' => '+966'.$row['office_phone'] ?? null,
                ]);
            }
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
