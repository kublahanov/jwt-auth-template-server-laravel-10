<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * RegisterRequest.
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
        return [
            'name' => ['required', 'string', "between:$this->minNameLength,$this->maxNameLength"],
            'email' => ['required', 'string', 'email', "max:$this->maxEmailLength", 'unique:' . (new User)->getTable()],
            'password' => ['required', 'string', 'confirmed', "min:$this->minPasswordLength"],
        ];
    }
}
