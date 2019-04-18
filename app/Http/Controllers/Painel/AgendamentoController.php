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
                        return redirect('/maio')->with(['sucess' => 'Agendamento cadastrado com sucesso.']);
                }
            }
        }
    }

    public function storeManutencao(AgendamentoFormRequest $request, Monitorado $monitorado, Agendamento $agendamento)
    {   
        $compareceu = $request->input('compareceu');

        if($compareceu == 'sim'){
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
            $insert = $this->manutencao->create([
                'cinta'     => $cinta,
                'carregador'       => $carregador,
                'tornozeleira'        => $tornozeleira,
                'compareceu'        => $compareceu,
                'agendamento_id' => $request->input('id_agendamento')
            ]);
            if($insert)
                return redirect()->back()->with(['sucess' => 'Manutencao cadastrada com sucesso.']);
        }

        if($compareceu == 'nao' || $compareceu == 'reagendou' ){
            $cinta = false;
            $carregador = false;
            $tornozeleira = false;        
            
            $insert = $this->manutencao->create([
                'cinta'     => $cinta,
                'carregador'       => $carregador,
                'tornozeleira'        => $tornozeleira,
                'compareceu'        => $compareceu,
                'agendamento_id' => $request->input('id_agendamento')
            ]);
            if($insert)
                return redirect()->back()->with(['sucess' => 'Manutencao cadastrada com sucesso.']);
        }
        return redirect()->back()->with(['errors' => 'Não foi possivel cadastrar a manutenção.']);
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
    		return redirect()->back()->with(['sucess' => 'Agendamento excluído com sucesso.']);
    	else
    		return redirect()->back()->with(['errors' => 'Erro ao excluir agendamento.']);
    }
    
    /* Exclusão somente para administradores - Futuro Pacth */
    public function destroyComManutencao($idAgendamento, $idManutencao)
    {   
        $manutencao = $this->manutencao->fin($idManutencao);
        $deleteManutencao = $manutencao->delete();
        $agendamento = $this->agendamento->find($idAgendamento);
    	$deleteAgendamento = $agendamento->delete();

    	if($deleteManutencao && $deleteAgendamento)
    		return redirect()->back()->with(['sucess' => 'Agendamento excluído com sucesso.']);
    	else
    		return redirect()->back()->with(['errors' => 'Erro ao excluir agendamento e manutenção.']);
    }

    /* ------------- LISTA DE AGENDAMENTO DOS MESES 2019  ------------- */

    public function listaAgendamentoJaneiro(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
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
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                $manutencao = $manutencaoDB[0];
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.maio', compact('lista'));
    }

    public function listaAgendamentoFevereiro(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
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
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                $manutencao = $manutencaoDB[0];
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.maio', compact('lista'));
    }

    public function listaAgendamentoMarco(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
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
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                $manutencao = $manutencaoDB[0];
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.maio', compact('lista'));
    }

    public function listaAgendamentoAbril(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
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
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                $manutencao = $manutencaoDB[0];
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.maio', compact('lista'));
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
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                $manutencao = $manutencaoDB[0];
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.maio', compact('lista'));
    }

    public function listaAgendamentoJunho(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
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
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                $manutencao = $manutencaoDB[0];
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.maio', compact('lista'));
    }

    public function listaAgendamentoJulho(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
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
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                $manutencao = $manutencaoDB[0];
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.maio', compact('lista'));
    }

    public function listaAgendamentoAgosto(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
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
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                $manutencao = $manutencaoDB[0];
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.maio', compact('lista'));
    }
    public function listaAgendamentoSetembro(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
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
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                $manutencao = $manutencaoDB[0];
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.maio', compact('lista'));
    }

    public function listaAgendamentoOutubro(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
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
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                $manutencao = $manutencaoDB[0];
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.maio', compact('lista'));
    }

    public function listaAgendamentoNovembro(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
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
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                $manutencao = $manutencaoDB[0];
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.maio', compact('lista'));
    }

    public function listaAgendamentoDezembro(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
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
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                $manutencao = $manutencaoDB[0];
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.maio', compact('lista'));
    }

}
