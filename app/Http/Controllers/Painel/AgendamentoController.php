<?php

namespace App\Http\Controllers\Painel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Painel\Agendamento;
use App\Models\Painel\Monitorado;
use App\Models\Painel\Manutencao;
use App\Models\Painel\AgendamentoMonitorado;
use App\Http\Requests\AgendamentoFormRequest;
use Illuminate\Support\Facades\DB;

class AgendamentoController extends Controller
{
    
    private $agendamento;
    private $monitorado;
    private $manutencao;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function __construct(Agendamento $agendamento, Monitorado $monitorado, Manutencao $manutencao)
	{
        $this->agendamento = $agendamento;
        $this->monitorado = $monitorado;
        $this->manutencao = $manutencao;
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AgendamentoFormRequest $request, Monitorado $monitorado, Agendamento $agendamento)
    {
        $requestIDMonitorado = $request->input('id_monitorado');
        $monitorado = DB::table('monitorado')->where('id_monitorado', '=', $requestIDMonitorado)->get();
        //$monitorado = $this->monitorado->where('id_monitorado', $requestIDMonitorado);
        //dd($monitorado);
        if($monitorado != null){
            //Verificação da qtd de agendamentos
            $data = $request->input('data_hora');
            $posicao = $request->input('posicao');
            $data_hora = date('Y/m/d H:i', strtotime($data));
            //dd($data_hora);
            $agendamentoDB = DB::table('agendamento')->where('data_hora', '=', $data_hora)->get();
            //dd($agendamentoDB);
            if(sizeOf($agendamentoDB) < 2){
                foreach($monitorado as $temp){
                    $motivo = $request->input('motivo');
                    $monitorado_id = $temp->id;
                    //dd($data_hora, $motivo, $monitorado_id);
                    $insert = $this->agendamento->create([
                        'data_hora'     => $data_hora,
                        'posicao'       => $posicao,
                        'motivo'        => $motivo,
                        'monitorado_id' => $monitorado_id
                    ]);
                    if($insert)
                        return redirect('/maio');
                }
            }
        }
    }

    public function storeManutencao()
    {   
        $cinta = false;
        $carregador = false;
        $tornozeleira = false;        
        if($request->input('ct') != null){
            $cinta = true;
        }
        if($request->input('c') != null){
            $carregador = true;
        }
        if($request->input('t') !=  null){
            $tornozeleira = true;
        }
        return "CADASTRO MANUTENCAO";
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $agendamento = $this->agendamento->find($id);
    	$delete = $agendamento->delete();

    	if($delete)
    		return redirect()->back();
    	else
    		return redirect()->back()->with(['errors' => 'Falha ao editar']);
    }
    
    /* ------------- LISTA DE AGENDAMENTO DOS MESES 2019  ------------- */

    public function listaAgendamentoJaneiro(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-01-01', '2019-01-31'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                array_push($lista, $agendamentomonitorado);
            }
        }
        return view('site.janeiro', compact('lista'));
    }

    public function listaAgendamentoFevereiro(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-02-01', '2019-02-30'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                array_push($lista, $agendamentomonitorado);
            }
        }
        return view('site.fevereiro', compact('lista'));
    }

    public function listaAgendamentoMarco(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-03-01', '2019-03-31'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                array_push($lista, $agendamentomonitorado);
            }
        }
        return view('site.marco', compact('lista'));
    }

    public function listaAgendamentoAbril(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-04-01', '2019-04-30'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                array_push($lista, $agendamentomonitorado);
            }
        }
        return view('site.abril', compact('lista'));
    }

    public function listaAgendamentoMaio(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-05-01', '2019-05-31'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                $agendamentomonitorado->manutencao = $this->manutencao->find($agendamento->id);
                array_push($lista, $agendamentomonitorado);
            }
        }
        return view('site.maio', compact('lista'));
    }

    public function listaAgendamentoJunho(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-06-01', '2019-06-30'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                array_push($lista, $agendamentomonitorado);
            }
        }
        return view('site.junho', compact('lista'));
    }

    public function listaAgendamentoJulho(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-07-01', '2019-07-31'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                array_push($lista, $agendamentomonitorado);
            }
        }
        return view('site.julho', compact('lista'));
    }

    public function listaAgendamentoAgosto(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-08-01', '2019-08-31'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                array_push($lista, $agendamentomonitorado);
            }
        }
        return view('site.agosto', compact('lista'));
    }

    public function listaAgendamentoSetembro(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-09-01', '2019-09-30'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                array_push($lista, $agendamentomonitorado);
            }
        }
        return view('site.setembro', compact('lista'));
    }

    public function listaAgendamentoOutubro(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-10-01', '2019-10-31'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                array_push($lista, $agendamentomonitorado);
            }
        }
        return view('site.outubro', compact('lista'));
    }

    public function listaAgendamentoNovembro(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-11-01', '2019-11-30'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                array_push($lista, $agendamentomonitorado);
            }
        }
        return view('site.novembro', compact('lista'));
    }

    public function listaAgendamentoDezembro(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-12-01', '2019-12-31'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                array_push($lista, $agendamentomonitorado);
            }
        }
        return view('site.dezembro', compact('lista'));
    }

}
