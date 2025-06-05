<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class VendorWalletRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
         return [

            'amount' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($this->input('type') == 1) {
                        $vendor = auth()->user();
                        if ($value > $vendor->balance) {
                            $fail(' لا يوجد رصيد كافي في حسابك.');
                        }
                    }
                },
            ],
            'type' => 'required|in:0,1',
            'note'=>'required|string',
        ];
    }


}
