<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubTaskRequest extends FormRequest
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
            'task_id' => 'required|exists:tasks,id', // Taskni tanlash shart, va u mavjud bo'lishi kerak
            'title' => 'required|string|max:255', // Title majburiy va maksimal uzunligi 255 bo'lishi kerak
            'category' => 'required|string|max:255', // Category majburiy va maksimal uzunligi 255 bo'lishi kerak
            'min' => 'required|integer|min:0', // Min qiymati integer va 0 yoki undan katta bo'lishi kerak
            'max' => 'required|integer|min:0|gte:min',
        ];
    }
}
