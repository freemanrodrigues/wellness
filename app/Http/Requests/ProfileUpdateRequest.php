<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'firstname'  => ['required', 'string', 'max:100'],
            'lastname'   => ['required', 'string', 'max:100'],
            'email'      => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone'      => ['nullable', 'string', 'max:20'],
            'address1'   => ['nullable', 'string', 'max:255'],
            'address2'   => ['nullable', 'string', 'max:255'],
            'landmark'   => ['nullable', 'string', 'max:255'],
            'city'       => ['nullable', 'string', 'max:100'],
            'pincode'    => ['nullable', 'string', 'max:10'],
            'state'      => ['nullable', 'string', 'max:100'],
            'country_id' => ['nullable', 'integer'],
        ];
    }
}
