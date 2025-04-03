<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'operation' => 'required|in:0,1',
            'investor_id' => 'required|exists:investors,id',
            'category_id' => 'required|exists:categories,id',
            'branch_id' => 'required|exists:branches,id',
            'quantity' => 'required|numeric',
            'total_price_add' => 'required_if:operation,1',
            'vendor_commission' => 'required_if:operation,1',
            'investor_commission' => 'required_if:operation,1',
            'sell_diff' => 'required_if:operation,1',
            'total_price_sub' => 'required_if:operation,0',
        ];

    }


}
