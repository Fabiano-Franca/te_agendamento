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
        $dataForm = $request->only(['id_monitorado', 'nome']);
        $insert = $this->monitorado->create($dataForm);

        if($insert)
            return redirect('/lista_monitorados')->with(['sucess' => 'Monitorado cadastrado com sucesso.']);
    }

    public function edit($id, Monitorado $monitorado)
    {
        $monitorado = $this->monitorado->find($id);
        $monitorados = $monitorado->all();

        return view('site.lista_monitorados', compact('monitorado','monitorados'));
    }

    public function update(MonitoradoFormRequest $request, $id)
    {
        $dataForm = $request->all();
        $monitorado_update = $this->monitorado->find($id);
    	$update = $monitorado_update->update($dataForm);

    	if($update)
    		return redirect('/lista_monitorados')->with(['sucess' => 'Monitorado alterado com sucesso.']);
    	else
    		return redirect('/lista_monitorados')->with(['errors' => 'Falha ao editar monitorado.']);
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
        $monitorado_delete = $this->monitorado->find($id);
    	$delete = $monitorado_delete->delete();

    	if($delete)
    		return redirect('/lista_monitorados')->with(['sucess' => 'Monitorado excluído com sucesso.']);
    	else
    		return redirect('/lista_monitorados')->with(['errors' => 'Falha ao excluír o monitorado.']);
    }
}
