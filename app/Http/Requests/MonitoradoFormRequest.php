<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MonitoradoFormRequest extends FormRequest
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
            'id_monitorado' => 'required|numeric|min:3',
            'nome'          => 'required|min:3|max:100'
        ];
    }

    public function messages()
    {
        return [
            'id_monitorado.required' =>'O campo ID é de preenchimento obrigatório',
            'id_monitorado.numeric' =>'Precisa ser apenas números',
            'id_monitorado.min' =>'Precisa ter no minimo 3 números',
            'nome.required' => 'O campo nome é de preenhimento obrigatório'
        ];
    }
}
