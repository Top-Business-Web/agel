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

class UnsurpassedImport implements ToCollection, WithHeadingRow
{
    /**
     * Process the imported Excel data.
     *
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
//        dd($collection);
       foreach ($collection as $row) {
    if (!$row->get('name')) {
        continue; // Skip rows with missing 'name'
    }

    $unsurpassed = Unsurpassed::where('national_id', $row->get('national_id'))
        ->first();

    if ($unsurpassed) {
        $unsurpassed->update([
            'name' => $row->get('name') ?? $unsurpassed->name,
            'phone' => '+966' . $row->get('phone') ?? $unsurpassed->phone,
            'national_id' => $row->get('national_id') ?? $unsurpassed->national_id,
            'office_name' => $row->get('office_name') ?? $unsurpassed->office_name,
            'office_phone' => '+966' . ($row->get('office_phone') ?? $unsurpassed->office_phone),
        ]);
    } else {
        Unsurpassed::create([
            'name' => $row->get('name'),
            'phone' => '+966' . $row->get('phone'),
            'national_id' => $row->get('national_id'),
            'office_name' => $row->get('office_name'),
            'office_phone' => '+966' . $row->get('office_phone'),
        ]);
    }
}
    }


}
