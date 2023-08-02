<?php

namespace App\Http\Requests;

use App\Models\TimeLog;
use App\Traits\TimelogTrait;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TimelogUpdateRequest extends FormRequest
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
                function ($attribute, $value, $fail) {
                    $timelog = TimeLog::findOrFail($this->route('timelog'));
                    $create_at = Carbon::parse($timelog->created_at)->toDateString();

                    $start_time = $this->setDateTime($value, Carbon::parse($create_at));
                    $end_time = $this->setDateTime($this->get('end_time'), Carbon::parse($create_at));
                    if ($timelog->start_time !== $start_time || $timelog->end_time !== $end_time) {
                        
                        $exist = TimeLog::where('id', '<>', $this->route('timelog'))
                        ->where(function ($query) use ($start_time, $end_time) {
                            $query->where(function ($subQuery) use ($start_time, $end_time) {
                                $subQuery->where('start_time', '<=', $end_time)
                                    ->where('end_time', '>=', $start_time);
                            })
                            ->orWhere(function ($subQuery) use ($start_time, $end_time) {
                                $subQuery->where('start_time', '<=', $end_time)
                                    ->where('end_time', '>=', $end_time);
                            })
                            ->orWhere(function ($subQuery) use ($start_time, $end_time) {
                                $subQuery->where('start_time', '>=', $start_time)
                                    ->where('end_time', '<=', $end_time);
                            });
                        })
                        ->get();
                        
                        if (!$exist->isEmpty()) {
                            $fail('Time exist.');
                        }
                    }
                }
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
