<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{

    protected $userIdBeingEdited;

    public function __construct($userIdBeingEdited)
    {
        $this->userIdBeingEdited = $userIdBeingEdited;
    }
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->userIdBeingEdited,
            'user_type' => 'required',
            'password' => $this->userIdBeingEdited ? 'nullable|min:6' : 'required|min:6',
        ];
    }
    
}
