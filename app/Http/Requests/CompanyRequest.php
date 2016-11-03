<?php

namespace Webefficiency\Http\Requests;

use Webefficiency\Http\Requests\Request;

class CompanyRequest extends Request
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
            'group_id' => 'required|integer',
            'fieldlogger_id' => 'required|integer',
            'name' => 'required',
            'active' => 'boolean'
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
            'group_id.required' => 'Grupo é obrigatório',
            'group_id.numeric'  => 'Valor incorreto no campo Grupo',
            'fieldlogger_id.required'  => 'Fieldlogger é obrigatório',
            'fieldlogger_id.numeric'  => 'Valor incorreto no campo Fieldlogger',
            'name.required'  => 'Nome é obrigatório',
            'active.boolean'  => 'Valor incorreto no campo Ativo'
        ];
    }
}
