<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'capacity'      => 'nullable|numeric',
            'hotel'         => 'nullable|exists:hotels,id',
            'category'      => 'nullable|exists:room_categories,id',
            'checkin_start' => 'nullable|date',
            'checkin_end'   => 'nullable|date',
        ];
    }
}
