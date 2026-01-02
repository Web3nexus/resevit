<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterBusinessOwnerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'country' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'phone_country_code' => ['required', 'string', 'max:10'],
            'business_name' => ['required', 'string', 'max:255'],
            'business_slug' => ['required', 'string', 'max:255', 'unique:tenants,slug'],
            'domain' => ['required', 'string', 'max:255', 'unique:tenants,domain'],
            'staff_range' => ['required', 'string', 'in:1-5,6-10,11-25,26-50,50+'],
        ];
    }
}
