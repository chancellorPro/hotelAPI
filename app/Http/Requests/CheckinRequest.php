<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckinRequest extends FormRequest
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
            'client_name'    => 'required|string|max:255',
            'phone'          => 'required|string|max:32',
            'voted_capacity' => 'nullable|numeric',
            'checkin_start'  => 'required|date',
            'checkin_end'    => 'required|date|after_or_equal:checkin_start',
            'room_id'        => 'required|exists:rooms,id',
        ];
    }
}
