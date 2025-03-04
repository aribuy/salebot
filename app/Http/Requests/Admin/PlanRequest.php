<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->is_free === null) {
            $this->merge(['is_free' => 0]);
        }
    }

    public function rules(): array
    {
        $planId = $this->route('plan');

        return [
            'name'               => 'required|unique:plans,name,'.$planId,
            'description'        => 'required',
            'price'              => 'required|numeric',
            'billing_period'     => 'required',
            'contact_limit'      => 'required|numeric',
            'campaigns_limit'    => 'required|numeric',
            'conversation_limit' => 'required|numeric',
            'team_limit'         => 'required|numeric',
            'is_free'            => 'nullable|boolean',
        ];
    }
}
