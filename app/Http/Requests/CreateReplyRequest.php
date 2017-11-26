<?php

namespace App\Http\Requests;

use App\Exceptions\ThrottleException;
use App\Reply;
use App\Thread;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string body
 */
class CreateReplyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Reply::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required|spam_free'
        ];
    }

    protected function failedAuthorization()
    {
        throw new ThrottleException(
            'You are replying too frequently. Please take a break.'
        );
    }

    public function persist (Thread $thread) {
        return $thread->replies()->create([
            'body' => $this->body,
            'user_id' => auth()->id()
        ])->load('owner');
    }
}
