<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgendamentoFormRequest extends FormRequest
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
            'id_monitorado' => 'required|min:3',
            'motivo'          => 'required|min:3|max:100'
        ];
    }

    public function messages()
    {
        return [
            'id_monitorado.required' =>'O campo id é de preenchimento obrigatório',
            'motivo.required' => 'O campo motivo é de preenchimento obrigatório'
        ];
    }
}
