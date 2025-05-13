<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentCreateRequest extends FormRequest
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
            'body' => 'required|string|min:1',
            'thread_id' => 'required|integer|exists:threads,id',
            'comment_parent_id'=> 'sometimes|integer|exists:comments,id',
        ];
    }
    public function messages(): array{
        return [
            'body.required'=> 'Comment text is required',
            'body.min'=> 'Comment text must be at least 1 character',
            'thread_id.required'=> 'Thread is required',
            'thread_id.integer'=> 'Thread must be an integer',
        ];
    }
}
