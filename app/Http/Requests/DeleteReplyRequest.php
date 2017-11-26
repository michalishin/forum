<?php

namespace App\Http\Requests;

use App\Reply;
use Illuminate\Foundation\Http\FormRequest;

class DeleteReplyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('delete', $this->route('reply'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    public function persist (Reply $reply) {
        $reply->delete();
    }
}
