<?php

namespace Webefficiency\Http\Requests;

use Webefficiency\Http\Requests\Request;

class UserRequest extends Request
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
            'company_id' => 'array|min:1|required',
            'name' => 'required',
            'email' => 'required|email|unique:users,email|sometimes',
            'password' => 'required|min:6|sometimes',
            'new_password' => 'min:6|sometimes',
            'is_admin' => 'boolean'
        ];
    }


    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'company_id.min' => 'Empresa é obrigatório',
            'company_id.required' => 'Empresa é obrigatório',
            'name.required'  => 'Nome é obrigatório',
            'email.required'  => 'Email é obrigatório',
            'email.email'  => 'Email incorreto',
            'email.unique'  => 'Este email já está cadastrado',
            'password.required'  => 'Senha é obrigatório',
            'password.min' => 'Senha deve ter pelo menos 6 caracteres',
            'new_password.min' => 'Senha deve ter pelo menos 6 caracteres',
        ];
    }
}
