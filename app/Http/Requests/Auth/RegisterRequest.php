<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * RegisterRequest.
 * @property string $name
 * @property string $email
 * @property string $password
 */
class RegisterRequest extends FormRequest
{
    protected int $minNameLength = 2;
    protected int $maxNameLength = 100;
    protected int $maxEmailLength = 100;
    protected int $minPasswordLength = 6;

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
            'name' => ['required', 'string', "between:$this->minNameLength,$this->maxNameLength"],
            'email' => ['required', 'email', "max:$this->maxEmailLength", "unique:$userTableName"],
            'password' => ['required', 'string', 'confirmed', "min:$this->minPasswordLength"],
        ];
    }
}
