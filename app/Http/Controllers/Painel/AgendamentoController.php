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
        try{
            $requestIDMonitorado = $request->input('id_monitorado');
            $monitorado = DB::table('monitorado')->where('id_monitorado', '=', $requestIDMonitorado)->get();
            if($monitorado != null){
                $data = $request->input('data_hora');
                $posicao = $request->input('posicao');
                $data_hora = date('Y/m/d H:i', strtotime($data));
                $agendamentoDB = DB::table('agendamento')->where('data_hora', '=', $data_hora)->get();
                if(sizeOf($agendamentoDB) < 2){
                    foreach($monitorado as $temp){
                        $motivo = $request->input('motivo');
                        $monitorado_id = $temp->id;
                        $insert = $this->agendamento->create([
                            'data_hora'     => $data_hora,
                            'posicao'       => $posicao,
                            'motivo'        => $motivo,
                            'monitorado_id' => $monitorado_id
                        ]);
                        if($insert)
                            return redirect('/maio')->with('mensagem', 'Agendamento cadastrado com sucesso.');
                    }
                }
            }
        }catch(\Exception $e){
            return redirect('/maio')->with('erro', 'Não foi possível cadastrar o agendamento. Reinicialize a página!');
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

            try{
                $insert = $this->manutencao->create([
                    'cinta'     => $cinta,
                    'carregador'       => $carregador,
                    'tornozeleira'        => $tornozeleira,
                    'compareceu'        => $compareceu,
                    'agendamento_id' => $request->input('id_agendamento')
                ]);
            }catch(\Exception $e){
                return redirect()->back()->with('erro', 'Não foi possivel cadastrar a manutenção.');
            }
            if($insert)
                return redirect()->back()->with('mensagem', 'Manutencao cadastrada com sucesso.');
        }

        if($compareceu == 'nao' || $compareceu == 'reagendou' ){
            $cinta = false;
            $carregador = false;
            $tornozeleira = false;        
            try{
                $insert = $this->manutencao->create([
                    'cinta'     => $cinta,
                    'carregador'       => $carregador,
                    'tornozeleira'        => $tornozeleira,
                    'compareceu'        => $compareceu,
                    'agendamento_id' => $request->input('id_agendamento')
                ]);
            }catch(\Exception $e){
                return redirect()->back()->with('erro', 'Não foi possivel cadastrar a manutenção.');
            }
            if($insert)
                return redirect()->back()->with('mensagem', 'Manutencao cadastrada com sucesso.');
        }
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
        try{
            $agendamento = $this->agendamento->find($id);
        	$delete = $agendamento->delete();
        }catch(\Exception $e){
            return redirect()->back()->with('erro', 'Erro ao excluir agendamento.');
        }
        if($delete)
    		return redirect()->back()->with('mensagem', 'Agendamento excluído com sucesso.');
    }
    
    /* Exclusão somente para administradores - Futuro Pacth */
    public function destroyComManutencao($idAgendamento, $idManutencao)
    {   
        try{
            $manutencao = $this->manutencao->fin($idManutencao);
            $deleteManutencao = $manutencao->delete();
            $agendamento = $this->agendamento->find($idAgendamento);
            $deleteAgendamento = $agendamento->delete();
        }catch(\Exception $e){
            return redirect()->back()->with('erro', 'Erro ao excluir agendamento e manutenção.');
        }
        if($deleteManutencao && $deleteAgendamento)
    		return redirect()->back()->with('mensagem', 'Agendamento excluído com sucesso.');
    }

    /* ------------- LISTA DE AGENDAMENTO DOS MESES 2019  ------------- */

    public function listaAgendamentoJaneiro(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-01-01', '2019-01-31'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $manutencaoDB = null;
                $manutencao = null;
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                if(isset($manutencaoDB[0])){
                    $manutencao = $manutencaoDB[0];
                }
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.janeiro', compact('lista'));
    }

    public function listaAgendamentoFevereiro(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-02-01', '2019-02-30'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $manutencaoDB = null;
                $manutencao = null;
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                if(isset($manutencaoDB[0])){
                    $manutencao = $manutencaoDB[0];
                }
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.fevereiro', compact('lista'));
    }

    public function listaAgendamentoMarco(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-03-01', '2019-03-31'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $manutencaoDB = null;
                $manutencao = null;
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                if(isset($manutencaoDB[0])){
                    $manutencao = $manutencaoDB[0];
                }
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.marco', compact('lista'));
    }

    public function listaAgendamentoAbril(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-04-01', '2019-04-30'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $manutencaoDB = null;
                $manutencao = null;
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                if(isset($manutencaoDB[0])){
                    $manutencao = $manutencaoDB[0];
                }
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.abril', compact('lista'));
    }

    public function listaAgendamentoMaio(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-05-01', '2019-05-31'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $manutencaoDB = null;
                $manutencao = null;
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                if(isset($manutencaoDB[0])){
                    $manutencao = $manutencaoDB[0];
                }
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        $lista_key_monitorado = array();
        $lista_nome_monitorado = array();
        $monitorados = $monitorado->all();
        foreach($monitorados as $monitorado){
            array_push($lista_key_monitorado, $monitorado->id_monitorado);
            array_push($lista_nome_monitorado, $monitorado->nome);
        }
        //dd($lista_monitorado);
        return view('site.maio', compact('lista', 'lista_key_monitorado', 'lista_nome_monitorado'));
    }

    public function listaAgendamentoJunho(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-06-01', '2019-06-30'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $manutencaoDB = null;
                $manutencao = null;
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                if(isset($manutencaoDB[0])){
                    $manutencao = $manutencaoDB[0];
                }
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.junho', compact('lista'));
    }

    public function listaAgendamentoJulho(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-07-01', '2019-07-31'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $manutencaoDB = null;
                $manutencao = null;
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                if(isset($manutencaoDB[0])){
                    $manutencao = $manutencaoDB[0];
                }
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.julho', compact('lista'));
    }

    public function listaAgendamentoAgosto(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-08-01', '2019-08-31'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $manutencaoDB = null;
                $manutencao = null;
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                if(isset($manutencaoDB[0])){
                    $manutencao = $manutencaoDB[0];
                }
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.agosto', compact('lista'));
    }
    public function listaAgendamentoSetembro(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-09-01', '2019-09-30'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $manutencaoDB = null;
                $manutencao = null;
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                if(isset($manutencaoDB[0])){
                    $manutencao = $manutencaoDB[0];
                }
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.setembro', compact('lista'));
    }

    public function listaAgendamentoOutubro(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-10-01', '2019-10-31'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $manutencaoDB = null;
                $manutencao = null;
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                if(isset($manutencaoDB[0])){
                    $manutencao = $manutencaoDB[0];
                }
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.outubro', compact('lista'));
    }

    public function listaAgendamentoNovembro(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-11-01', '2019-11-30'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $manutencaoDB = null;
                $manutencao = null;
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                if(isset($manutencaoDB[0])){
                    $manutencao = $manutencaoDB[0];
                }
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.novembro', compact('lista'));
    }

    public function listaAgendamentoDezembro(Agendamento $agendamento, Monitorado $monitorado, AgendamentoMonitorado $agendamentomonitorado)
    {
        //$agendamentos = $agendamento->all();
        $agendamentos = DB::table('agendamento')->whereBetween('data_hora', ['2019-12-01', '2019-12-31'])->get();
        $lista = array();
        if(isset($agendamentos) && $agendamentos != null){
            foreach($agendamentos as $agendamento){
                $manutencaoDB = null;
                $manutencao = null;
                $agendamentomonitorado = new AgendamentoMonitorado();
                $monitorado = $this->monitorado->find($agendamento->monitorado_id);
                $agendamentomonitorado->monitorado = $monitorado;
                $agendamentomonitorado->agendamento = $agendamento;
                $manutencaoDB = DB::table('manutencao')->where('agendamento_id', '=', $agendamento->id)->get();
                if(isset($manutencaoDB[0])){
                    $manutencao = $manutencaoDB[0];
                }
                $agendamentomonitorado->manutencao = $manutencao;
                array_push($lista, $agendamentomonitorado);
            }
        }
        //dd($lista);
        return view('site.dezembro', compact('lista'));
    }

}
