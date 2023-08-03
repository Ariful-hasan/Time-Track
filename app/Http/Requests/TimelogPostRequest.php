<?php

namespace App\Http\Requests;


use App\Rules\CreateTimeAvailabilityRule;
use App\Traits\TimelogTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TimelogPostRequest extends FormRequest
{
    use TimelogTrait;
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'project_id' => [
                'required',
                'integer',
                Rule::exists('projects', 'id'),
            ],
            'start_time' => [
                'required',
                'date_format:H:i',
                new CreateTimeAvailabilityRule
            ],
            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',
            ],
            'description' => 'nullable|string',
        ];
    }
}
