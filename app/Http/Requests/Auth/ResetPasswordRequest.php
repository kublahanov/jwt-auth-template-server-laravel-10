<?php

namespace App\Http\Requests\Auth;

use App\Services\AuthService;
use Illuminate\Foundation\Http\FormRequest;

/**
 * ResetPasswordRequest.
 *
 * @property string $email
 * @property string $token
 * @property string $password
 * @property string $password_confirmation
 */
class ResetPasswordRequest extends FormRequest
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
     * @return array[]
     */
    public function rules(): array
    {
        // $userTableName = User::getTableName();

        return [
            // 'email' => ['required', 'email', "exists:$userTableName,email"],
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed', 'min:' . AuthService::MIN_PASSWORD_LENGTH],
        ];
    }
}
