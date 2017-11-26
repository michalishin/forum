<?php

namespace App\Http\Requests;

use App\Reply;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReplyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     * @internal param Reply $reply
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('reply'));
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

    public function persist(Reply $reply) : Reply
    {
        $reply->update($this->all());
        return $reply;
    }
}
