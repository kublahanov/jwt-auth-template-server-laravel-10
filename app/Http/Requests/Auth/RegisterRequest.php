<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Http\FormRequest;

/**
 * RegisterRequest.
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $password_confirmation
 */
class RegisterRequest extends FormRequest
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
        $userTableName = User::getTableName();

        return [
            'name' => ['required', 'string', 'between:' . AuthService::MIN_NAME_LENGTH . ',' . AuthService::MAX_NAME_LENGTH],
            'email' => ['required', 'email', 'max:' . AuthService::MAX_EMAIL_LENGTH, "unique:$userTableName"],
            'password' => ['required', 'string', 'confirmed', 'min:' . AuthService::MIN_PASSWORD_LENGTH],
        ];
    }
}
