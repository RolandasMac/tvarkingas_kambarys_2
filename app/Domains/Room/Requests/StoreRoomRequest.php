<?php
namespace App\Domains\Room\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photo'       => 'required|image|mimes:jpg,jpeg,png',
            'time_of_day' => 'required|in:rytas,vakaras',
            'comment'     => 'nullable|string',
        ];
    }
}
