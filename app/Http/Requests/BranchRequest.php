<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
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
            'name' => 'required|unique:branches,name',
            'region_id' => 'required|exists:regions,id',
        ];
    }

    protected function update(): array
    {
        return [
            'name' => 'nullable|unique:branches,name,' . $this->branch,
            'region_id' => 'nullable|exists:regions,id',
        ];
    }
}
