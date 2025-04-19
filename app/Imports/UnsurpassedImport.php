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
        foreach ($collection as $row) {


            $unsurpassed = Unsurpassed::where('national_id', $row['national_id'])
                ->first();

            if ($unsurpassed) {
                $unsurpassed->update([
//<<<<<<< ismail
                    'name' => $row['name'] ?? $unsurpassed->name,
                    'phone' => '+966'.$row['phone'] ?? $unsurpassed->phone,
                    'office_name' => $row['office_name'] ?? $unsurpassed->office_name,
                    'office_phone' => '+966'.$row['office_phone'] ?? $unsurpassed->office_phone,
//=======
//                    'name' => $row['name'] ?? null,
//                    'phone' => '+966'.$row['phone'] ?? null,
//                    'office_name' => $row['office_name'] ?? null,
  //                  'office_phone' => '+966'.$row['office_phone'] ?? null,
//>>>>>>> main
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


}
