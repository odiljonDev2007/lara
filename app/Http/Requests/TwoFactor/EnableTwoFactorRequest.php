<?php

namespace Vanguard\Http\Requests\TwoFactor;

class EnableTwoFactorRequest extends TwoFactorRequest
{
    public function rules(): array
    {
        return [
            'country_code' => 'required|numeric|integer',
            'phone_number' => 'required|numeric',
        ];
    }
}
