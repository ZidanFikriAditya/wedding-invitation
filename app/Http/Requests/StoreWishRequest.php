<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWishRequest extends FormRequest
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
            'letter_invitation_id' => 'required|exists:letter_invitations,id',
            'wishes' => 'required|string',
            'other_people' => 'nullable|string',
            'confirmation' => 'required|in:datang,tidak datang',
        ];
    }
}
