<?php

namespace App\Http\Controllers\Painel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Painel\Monitorado;
use App\Http\Requests\MonitoradoFormRequest;

class MonitoradoController extends Controller
{
	private $monitorado;

	public function __construct(Monitorado $monitorado)
	{
		$this->monitorado = $monitorado;
	}

    public function index(Monitorado $monitorado)
    {
    	
        $monitorados = $monitorado->all();
    	return view('site.home.index', compact('monitorados'));
    }

    public function lista_monitorados(Monitorado $monitorado)
    {
        $monitorados = $monitorado->all();
        return view('site.lista_monitorados', compact('monitorados'));
    }

    public function cadastro(MonitoradoFormRequest $request)
    {   
        try {
            $dataForm = $request->only(['id_monitorado', 'nome']);
            $insert = $this->monitorado->create($dataForm);
        }catch(\Exception $e){
            return back()->with('erro', 'Não foi possível cadastrar o monitorado.');
        }
        if($insert)
            return back()->with('mensagem', 'Monitorado cadastrado com sucesso.');
    }

    public function edit($id, Monitorado $monitorado)
    {
        $monitorado = $this->monitorado->find($id);
        $monitorados = $monitorado->all();

        return view('site.lista_monitorados', compact('monitorado','monitorados'));
    }

    public function update(MonitoradoFormRequest $request, $id)
    {   
        try{
            $dataForm = $request->all();
            $monitorado_update = $this->monitorado->find($id);
            $update = $monitorado_update->update($dataForm);
        }catch(\Exception $e ){
            return redirect('/lista_monitorados')->with(['errors' => 'Falha ao editar monitorado.']);
        }
        if($update)
    		return redirect('/lista_monitorados')->with(['success' => 'Monitorado alterado com sucesso.']);
    }

    public function procura_monitorado(MonitoradoFormRequest $request, $id)
    {
        $dataForm = $request->all();
        $monitorado_update = $this->monitorado->find($id);
    	
    	if($monitorado_update != null)
    		return redirect('')->back();
    	else
    		return redirect('/lista_monitorados')->with(['errors' => 'Falha ao editar']);
    }

    public function destroy($id)
    {
        try{
            $monitorado_delete = $this->monitorado->find($id);
    	    $delete = $monitorado_delete->delete();
        }catch(\Exception $e ){
            return redirect('/lista_monitorados')->with(['errors' => 'Falha ao excluír o monitorado.']);
        }
        if($delete)
    		return redirect('/lista_monitorados')->with(['success' => 'Monitorado excluído com sucesso.']);
    }
}
