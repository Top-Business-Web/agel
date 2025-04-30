<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Facades\Excel;

class UnsurpassedExampleExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Data' => new DataSheet(),
            'Instructions' => new InstructionsSheet(),
        ];
    }
}

class DataSheet implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            ['', '', '', '', ''],
        ];
    }

    public function headings(): array
    {
        return [
            'name',
            'phone (without +966)',
            'national_id',
            'office_name',
            'office_phone (without +966)',
        ];
    }
}

class InstructionsSheet implements FromArray
{
    public function array(): array
    {
        return [
            ['Instructions'],
            ['1. Fill in the data in the "Data" sheet'],
            ['2. Phone numbers will be automatically prefixed with +966'],
            ['3. Do not modify the header row'],
            ['4. National ID must be unique'],
            ['5. Remove this instruction sheet before uploading'],
        ];
    }
}
