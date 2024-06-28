<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * SendResetPasswordLinkRequest.
 * @property string $email
 */
class SendResetPasswordLinkRequest extends FormRequest
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
        $userTableName = (new User)->getTable();

        return [
            'email' => ['required', 'email', "exists:$userTableName,email"],
        ];
    }
}
