<?php

namespace App\Http\Requests;

use App\Enums\UserType;
use App\Rules\VideoDurationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class DedicationRequest extends FormRequest
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
            'dedication_type_id' => 'required|exists:dedication_types,id',
            'user_id' => [
                'required', 
                Rule::exists('users', 'id')->where('type', UserType::CLIENT->value)
             ],
            'artist_id' => [
                'required', 
                Rule::exists('users', 'id')->where('type', UserType::ARTIST->value)
             ],
            'message' => 'required|string|max:255',
            'dedication_video' => [
                'required',
                'file',
                'mimes:mp4,mov,avi,wmv,mkv',
                'max:102400',
                new VideoDurationRule(600)
            ],
            'is_self' => 'required|string',
            'use_phone_user_for_payment' => 'required|string',
            'firstname' => 'required_if:is_self,false|string',
            'lastname' => 'required_if:is_self,false|string',
            'email' => 'required_if:is_self,false|string',
            'phone_payment' => 'required_if:use_phone_user_for_payment,false|string|regex:/^0[76][0-9]{7}$/'
        ];
    }

    public function messages()
    {
        return [
            'phone_payment.regex' => 'The phone field must start with 0, followed by a 6 or a 7, and must contain exactly 9 digits.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'message' => 'Les données fournies sont invalides.',
            'errors'  => $validator->errors(),
        ], 422);

        throw new HttpResponseException($response);
    }
}
