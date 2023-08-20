<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['required','string','max:191'],
            'email' =>  ['required','string','email','max:191'],
            // 登録済みユーザーの重複防止処置を追加すること！！（Userテーブル作成後）
            'password' => ['required','min:8','max:191']
        ];
    }
}
