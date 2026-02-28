<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Allow request to proceed
     * Authorization will be handled by middleware (role:admin)
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validate incoming user creation data
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'username'   => ['required', 'string', 'max:50', 'unique:users,username'],
            'email'      => ['required', 'email', 'max:150', 'unique:users,email'],
            'phone_no'      => ['required', 'string', 'max:15'],
            'password'   => ['required', 'string', 'min:6'],
            'role'       => ['required', 'string', 'exists:roles,name'],
        ];
    }
    public function after(): array
    {
        return [
            function ($validator) {
                if ($this->role === 'super_admin') {
                    $validator->errors()->add('role', 'You cannot assign super admin role.');
                }
            }
        ];
    }
}