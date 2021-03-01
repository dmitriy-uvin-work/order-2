<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchasingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'confirm_use' => 'required',
            'phone' => 'required|numeric',
            'delivery_type' => 'required',
            'payment_type' => 'required',
            'total_price' => 'required|numeric',
            'delivery_region' => 'required_if:delivery_type,==,1',
            'delivery_address' => 'required_if:delivery_type,==,1',
        ];
    }
}
