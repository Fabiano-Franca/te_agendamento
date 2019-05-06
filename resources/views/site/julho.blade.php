@extends('site.template.template_geral')

@section('content')
<table class="table table-striped">
    <tbody>
	@if(isset($errors) && (count($errors) > 0))
		<div class="alert alert-danger" role="alert">
			@foreach( $errors->all() as $error)
				<p>{{$error}}</p>
			@endforeach
		</div>
	@endif
    @if(session('mensagem'))
		<div class="alert alert-success">
			<p>{{session('mensagem')}}</p>
		</div>
	@endif
	@if(session('erro'))
		<div class="alert alert-danger">
			<p>{{session('mensagem')}}</p>
		</div>
	@endif
    @if(isset($lista))
        @for($dia = 1, $itemAnterior = null, $diaFeriado = array(), $finalSemana = array(6, 7, 13, 14, 20, 21, 27, 28); $dia <= 31; $dia++)
        @if(in_array($dia, $diaFeriado, true)) <!-- Inicio IF $diaFeriado-->
                @if($dia < 10)
                    <thead>
                        <th class='diaFeriado' colspan='9'> FERIADO - 0{{$dia}}/07/2019</th>
                    </thead>
                @else
                    <thead>
                        <th class='diaFeriado' colspan='9'> FERIADO - {{$dia}}/07/2019</th>
                    </thead>
                @endif

                @elseif(in_array($dia, $finalSemana, true))
                    @if($dia < 10)
                        <thead>
                            <th class='finalDeSemana' colspan='9'>Final de Semana (Sab/Dom) - 0{{$dia}}/07/2019</th>
                        </thead>
                    @else
                        <thead>
                            <th class='finalDeSemana' colspan='9'>Final de Semana (Sab/Dom) - {{$dia}}/07/2019</th>
                        </thead>
                    @endif
                @else
                    @if($dia < 10)
                        <thead>
                            <th class='dia_agendamento' colspan='9'>0{{$dia}}/07/2019</th>
                        </thead>
                    @else
                        <thead>
                            <th class='dia_agendamento' colspan='9'>{{$dia}}/07/2019</th>
                        </thead>
                    @endif
                    @for($horario = 9; $horario <= 12; $horario++)
                        @for($qtd = 1, $itemAtual = null, $agendamento = false; $qtd <= 2; $qtd++)
                            @foreach($lista as $item)
                                @if($dia < 10)
                                    @if($horario < 10)
                                        @if("2019-05-0".$dia." 0".$horario.":00:00" === $item->agendamento->data_hora && $item->agendamento->posicao === $qtd)
                                        <?php   $agendamento = true;
                                                    $itemAtual = $item ?>
                                        @endif
                                    @else
                                        @if("2019-05-0".$dia." ".$horario.":00:00" === $item->agendamento->data_hora && $item->agendamento->posicao === $qtd)
                                            <?php   $agendamento = true;
                                                    $itemAtual = $item ?>
                                        @endif
                                    @endif
                                @else
                                    @if($horario < 10)
                                        @if("2019-05-".$dia." 0".$horario.":00:00" === $item->agendamento->data_hora && $item->agendamento->posicao === $qtd)
                                            <?php   $agendamento = true;
                                                    $itemAtual = $item ?>
                                        @endif
                                    @else
                                        @if("2019-05-".$dia." ".$horario.":00:00" === $item->agendamento->data_hora && $item->agendamento->posicao === $qtd)
                                            <?php   $agendamento = true;
                                                    $itemAtual = $item ?>
                                        @endif
                                    @endif
                                @endif
                            @endforeach
                            @if($horario == 12 && $qtd == 2)
                                @break;
                            @endif
                            <!-- DIAS DA SEMANA *** MANHA **** -->
                            @if($agendamento)
                                @if(isset($itemAtual->manutencao)) <!-- AGENDAMENTO COM MANUTENCAO -->
                                    {{$agendamento = false}}
                                    <form method="post" id="mail_form{{$dia}}{{$horario}}{{$qtd}}">
                                    {!! csrf_field() !!}
                                        <tr id='2019-05-{{$dia}} {{$horario}}:00'>
                                            <td class='horario_td'>
                                                {{$horario}}:00
                                                <input type="int" class="posicao" id='id_agendamento' name="id_agendamento" value="{{$itemAtual->agendamento->id}}"/>
                                                <input type="number" class="posicao" id='posicao' name="posicao" value="{{$itemAtual->agendamento->posicao}}"/>
                                                <input type="datetime" class="campo_hora" id='data_hora' name="data_hora" value="{{$itemAtual->agendamento->data_hora}}"/>
                                            </td>
                                            <td class='id_td'>
                                                <input disabled='true' type='text' id='id_monitorado' name='id_monitorado' class='id_mon form-control' value="{{$itemAtual->monitorado->id_monitorado}}"/>
                                            </td>
                                            <td class='nome_td'>
                                                <input disabled='true' class='nome_input form-control' id="nome_monitorado" name='nome_monitorado' type="text" value="{{$itemAtual->monitorado->nome}}">
                                            </td>
                                            <td class='motivo_td form-control'>
                                                <select disabled='true' class='achou motivo_agendamento' name='motivo'>
                                                    <option value='{{$itemAtual->agendamento->motivo}}'>{{$itemAtual->agendamento->motivo}}</option>
                                                </select>
                                            </td>
                                            <td class='materiais_td'>
                                                @if($itemAtual->manutencao->cinta)
                                                    <input disabled='true' class='form-control' type="checkbox" id="ct" name="ct" value="x" checked >
                                                @else
                                                    <input disabled='true' class='form-control' type="checkbox" id="ct" name="ct" value="x">
                                                @endif
                                            </td>
                                            <td class='materiais_td'>
                                                @if($itemAtual->manutencao->carregador)
                                                    <input disabled='true' class='form-control' type="checkbox" id="c" name="c" value="x" checked>
                                                @else
                                                    <input disabled='true' class='form-control' type="checkbox" id="c" name="c" value="x">
                                                @endif
                                            </td>
                                            <td class='materiais_td'>
                                                @if($itemAtual->manutencao->tornozeleira)
                                                    <input disabled='true' class='form-control' type="checkbox" id="t" name="t" value="x" checked >
                                                @else
                                                    <input disabled='true' class='form-control' type="checkbox" id="t" name="t" value="x">
                                                @endif
                                            </td>
                                            <td class='compareceu_td'>
                                                <select class='compareceu' class='form-control' name='compareceu' disabled='true'>
                                                    <option value='{{$itemAtual->manutencao->compareceu}}' selected>{{$itemAtual->manutencao->compareceu}}</option>
                                                </select>
                                            </td>
                                            <td class='acao_agend'>
                                                <a class='position action add'>
                                                    <span class='glyphicon glyphicon-plus'></span>
                                                </a>
                                                                                            
                                                <a class='position action delete'>
                                                    <span class='glyphicon glyphicon-trash'></span>
                                                </a>
                                                
                                                <a class='position action edit'>
                                                    <span class='glyphicon glyphicon-ok'></span>
                                                </a>

                                            </td>
                                        </tr>
                                    </form>
                                @else   <!-- SO AGENDAMENTO -->
                                    {{$agendamento = false}}
                                    <form method="post" id="mail_form{{$dia}}{{$horario}}{{$qtd}}">
                                    {!! csrf_field() !!}
                                        <tr id='2019-05-{{$dia}} {{$horario}}:00'>
                                            <td class='horario_td'>
                                                {{$horario}}:00
                                                <input type="int" class="posicao" id='id_agendamento' name="id_agendamento" value="{{$itemAtual->agendamento->id}}"/>
                                                <input type="number" class="posicao" id='posicao' name="posicao" value="{{$itemAtual->agendamento->posicao}}"/>
                                                <input type="datetime" class="campo_hora" id='data_hora' name="data_hora" value="{{$itemAtual->agendamento->data_hora}}"/>
                                            </td>
                                            <td class='id_td'>
                                                <input readonly="readonly" type='text' id='id_monitorado' name='id_monitorado' class='id_mon form-control' value="{{$itemAtual->monitorado->id_monitorado}}"/>
                                            </td>
                                            <td class='nome_td'>
                                                <input readonly="readonly" class='nome_input form-control' id="nome_monitorado" name='nome_monitorado' type="text" value="{{$itemAtual->monitorado->nome}}">
                                            </td>
                                            <td class='motivo_td'>
                                                <select readonly="readonly" class='achou motivo_agendamento form-control' name='motivo'>
                                                    <option value='{{$itemAtual->agendamento->motivo}}'>{{$itemAtual->agendamento->motivo}}</option>
                                                </select>
                                            </td>
                                            <td class='materiais_td'>
                                                <input type="checkbox" class='form-control' id="ct" name="ct" value="x">
                                            </td>
                                            <td class='materiais_td'>
                                                <input type="checkbox" class='form-control' id="c" name="c" value="x">
                                            </td>
                                            <td class='materiais_td'>
                                                <input type="checkbox" class='form-control' id="t" name="t" value="x">
                                            </td>
                                            <td class='compareceu_td'>
                                                <select class='compareceu form-control' name='compareceu'>
                                                    <option value='' selected>-</option>
                                                    <option value='sim'>Sim</option>
                                                    <option value='nao'>Não</option>
                                                    <option value='reagendou'>Reagendou</option>
                                                </select>
                                            </td>
                                            <td class='acao_agend'>
                                                <a class='position action add'>
                                                    <span class='glyphicon glyphicon-plus'></span>
                                                </a>
                                                                                            
                                                <a href="{{url('/destroy_agend', $itemAtual->agendamento->id)}}" name='{{$dia}}{{$horario}}delete' class='position action delete'>
                                                    <span class='glyphicon glyphicon-trash'></span>
                                                </a>
                                                
                                                <button type="submit" onclick="actions('btn2', {{$dia}}, {{$horario}}, {{$qtd}})" class='position action edit'>
                                                    <span class='glyphicon glyphicon-ok'></span>
                                                </button>

                                            </td>
                                        </tr>
                                    </form>
                                @endif
                            @else
                                <form action="{{ url('/cadastro_agendamento') }}" method="post" id="mail_form{{$dia}}{{$horario}}{{$qtd}}">
                                {!! csrf_field() !!}
                                    <tr id='2019-05-{{$dia}} {{$horario}}:00'>
                                        <td class='horario_td'>
                                            {{$horario}}:00
                                            <input type="number" class="posicao" id='posicao' name="posicao" value="{{$qtd}}"/>
                                            <input type="datetime" class="campo_hora" id='data_hora' name="data_hora" value="2019-05-{{$dia}} {{$horario}}:00">
                                        </td>
                                        <td class='id_td'>
                                            <input type='text' id='id_monitorado' name='id_monitorado' class='id_mon form-control' placeholder='ID'>
                                        </td>
                                        <td class='nome_td'>
                                            <input class='nome_input form-control' id="nome_monitorado" name='nome_monitorado' placeholder='Nome monitorado' type="text">
                                        </td>
                                        <td class='motivo_td'>
                                            <select class='motivo_agendamento form-control' name='motivo' id='motivoComparecimento'>
                                                <option disabled selected>Selecione uma opção...</option>
                                                <option value='Tornozeleira rompida'>Tornozeleira rompida</option>
                                                <option value='Aparelho não liga'>Aparelho não liga</option>
                                                <option value='Troca de carregador'>Troca de carregador</option>
                                                <option value='Sem comunicação'>Sem comunicação</option>
                                                <option value='Sem sinal GPS'>Sem sinal GPS</option>
                                                <option value='Não segura carga'>Não segura carga</option>
                                                <option value='Reativação'>Reativação</option>
                                                <option value='Devolução'>Devolução</option>
                                                <option value='Pendente'>Pendente</option>
                                            </select>
                                        </td>
                                        <td class='materiais_td'>
                                            <input type="checkbox" class='form-control' name="ct" value="x">
                                        </td>
                                        <td class='materiais_td'>
                                            <input type="checkbox" class='form-control' name="c" value="x">
                                        </td>
                                        <td class='materiais_td'>
                                            <input type="checkbox" class='form-control' name="t" value="x">
                                        </td>
                                        <td class='compareceu_td'>
                                            <select class='compareceu form-control' name='compareceu'>
                                                <option value='' selected>-</option>
                                                <option value='sim'>Sim</option>
                                                <option value='nao'>Não</option>
                                                <option value='reagendou'>Reagendou</option>
                                            </select>
                                        </td>
                                        <td class='acao_agend'>
                                            <button type="submit" onclick="actions('btn1', {{$dia}}, {{$horario}}, {{$qtd}})" class='position action add'>
                                                <span class='glyphicon glyphicon-plus'></span>
                                            </button>
                                                                                        
                                            <a class='position action delete'>
                                                <span class='glyphicon glyphicon-trash'></span>
                                            </a>
                                            
                                            <a class='position action edit'>
                                                <span class='glyphicon glyphicon-ok'></span>
                                            </a>
                                        </td>
                                    </tr>
                                </form>
                            @endif
                        @endfor
                    @endfor

                    <!-- DIAS DA SEMANA *** TARDE **** -->
                    @for($horario = 14; $horario <= 15; $horario++)
                        @for($qtd = 1; $qtd <= 2; $qtd++)
                            @foreach($lista as $item)
                                @if(("2019-05".$dia." ".$horario.":00:00")  == $item->agendamento->data_hora)
                                    {{$agendamento = true}}
                                @endif
                            @endforeach

                            @if($agendamento)
                            @if(isset($itemAtual->manutencao)) <!-- AGENDAMENTO COM MANUTENCAO -->
                                    {{$agendamento = false}}
                                    <form method="post" id="mail_form{{$dia}}{{$horario}}{{$qtd}}">
                                    {!! csrf_field() !!}
                                        <tr id='2019-05-{{$dia}} {{$horario}}:00'>
                                            <td class='horario_td'>
                                                {{$horario}}:00
                                                <input type="int" class="posicao" id='id_agendamento' name="id_agendamento" value="{{$itemAtual->agendamento->id}}"/>
                                                <input type="number" class="posicao" id='posicao' name="posicao" value="{{$itemAtual->agendamento->posicao}}"/>
                                                <input type="datetime" class="campo_hora" id='data_hora' name="data_hora" value="{{$itemAtual->agendamento->data_hora}}"/>
                                            </td>
                                            <td class='id_td'>
                                                <input disabled='true' type='text' id='id_monitorado' name='id_monitorado' class='id_mon form-control' value="{{$itemAtual->monitorado->id_monitorado}}"/>
                                            </td>
                                            <td class='nome_td'>
                                                <input disabled='true' class='nome_input form-control' id="nome_monitorado" name='nome_monitorado' type="text" value="{{$itemAtual->monitorado->nome}}">
                                            </td>
                                            <td class='motivo_td'>
                                                <select disabled='true' class='achou motivo_agendamento form-control' name='motivo'>
                                                    <option value='{{$itemAtual->agendamento->motivo}}'>{{$itemAtual->agendamento->motivo}}</option>
                                                </select>
                                            </td>
                                            <td class='materiais_td'>
                                                @if($itemAtual->manutencao->cinta)
                                                    <input disabled='true' type="checkbox" class='form-control' id="ct" name="ct" value="x" checked >
                                                @else
                                                    <input disabled='true' type="checkbox" class='form-control' id="ct" name="ct" value="x">
                                                @endif
                                            </td>
                                            <td class='materiais_td'>
                                                @if($itemAtual->manutencao->carregador)
                                                    <input disabled='true' type="checkbox" class='form-control' id="c" name="c" value="x" checked>
                                                @else
                                                    <input disabled='true' type="checkbox" class='form-control' id="c" name="c" value="x">
                                                @endif
                                            </td>
                                            <td class='materiais_td'>
                                                @if($itemAtual->manutencao->tornozeleira)
                                                    <input disabled='true' type="checkbox" class='form-control' id="t" name="t" value="x" checked >
                                                @else
                                                    <input disabled='true' type="checkbox" class='form-control' id="t" name="t" value="x">
                                                @endif
                                            </td>
                                            <td class='compareceu_td'>
                                                <select class='compareceu form-control' name='compareceu' disabled='true'>
                                                    <option value='{{$itemAtual->manutencao->compareceu}}' selected>{{$itemAtual->manutencao->compareceu}}</option>
                                                </select>
                                            </td>
                                            <td class='acao_agend'>
                                                <a class='position action add'>
                                                    <span class='glyphicon glyphicon-plus'></span>
                                                </a>
                                                                                            
                                                <a class='position action delete'>
                                                    <span class='glyphicon glyphicon-trash'></span>
                                                </a>
                                                
                                                <a class='position action edit'>
                                                    <span class='glyphicon glyphicon-ok'></span>
                                                </a>

                                            </td>
                                        </tr>
                                    </form>
                                @else   <!-- SO AGENDAMENTO -->
                                    {{$agendamento = false}}
                                    <form method="post" id="mail_form{{$dia}}{{$horario}}{{$qtd}}">
                                    {!! csrf_field() !!}
                                        <tr id='2019-05-{{$dia}} {{$horario}}:00'>
                                            <td class='horario_td'>
                                                {{$horario}}:00
                                                <input type="int" class="posicao" id='id_agendamento' name="id_agendamento" value="{{$itemAtual->agendamento->id}}"/>
                                                <input type="number" class="posicao" id='posicao' name="posicao" value="{{$itemAtual->agendamento->posicao}}"/>
                                                <input type="datetime" class="campo_hora" id='data_hora' name="data_hora" value="{{$itemAtual->agendamento->data_hora}}"/>
                                            </td>
                                            <td class='id_td'>
                                                <input readonly="readonly" type='text' id='id_monitorado' name='id_monitorado' class='id_mon form-control' value="{{$itemAtual->monitorado->id_monitorado}}"/>
                                            </td>
                                            <td class='nome_td'>
                                                <input readonly="readonly" class='nome_input form-control' id="nome_monitorado" name='nome_monitorado' type="text" value="{{$itemAtual->monitorado->nome}}">
                                            </td>
                                            <td class='motivo_td'>
                                                <select readonly="readonly" class='achou motivo_agendamento form-control' name='motivo'>
                                                    <option value='{{$itemAtual->agendamento->motivo}}'>{{$itemAtual->agendamento->motivo}}</option>
                                                </select>
                                            </td>
                                            <td class='materiais_td'>
                                                <input type="checkbox" class='form-control' id="ct" name="ct" value="x">
                                            </td>
                                            <td class='materiais_td'>
                                                <input type="checkbox" class='form-control' id="c" name="c" value="x">
                                            </td>
                                            <td class='materiais_td'>
                                                <input type="checkbox" class='form-control' id="t" name="t" value="x">
                                            </td>
                                            <td class='compareceu_td'>
                                                <select class='compareceu form-control' name='compareceu'>
                                                    <option value='' selected>-</option>
                                                    <option value='sim'>Sim</option>
                                                    <option value='nao'>Não</option>
                                                    <option value='reagendou'>Reagendou</option>
                                                </select>
                                            </td>
                                            <td class='acao_agend'>
                                                <a class='position action add'>
                                                    <span class='glyphicon glyphicon-plus'></span>
                                                </a>
                                                                                            
                                                <a href="{{url('/destroy_agend', $itemAtual->agendamento->id)}}" name='{{$dia}}{{$horario}}delete' class='position action delete'>
                                                    <span class='glyphicon glyphicon-trash'></span>
                                                </a>
                                                
                                                <button type="submit" onclick="actions('btn2', {{$dia}}, {{$horario}}, {{$qtd}})" class='position action edit'>
                                                    <span class='glyphicon glyphicon-ok'></span>
                                                </button>

                                            </td>
                                        </tr>
                                    </form>
                                @endif
                            @else
                                <form action="{{ url('/cadastro_agendamento') }}" method="post" id="mail_form{{$dia}}{{$horario}}{{$qtd}}">
                                {!! csrf_field() !!}
                                    <tr id='2019-05-{{$dia}} {{$horario}}:00'>
                                        <td class='horario_td'>
                                            {{$horario}}:00
                                            <input type="number" class="posicao" id='posicao' name="posicao" value="{{$qtd}}"/>
                                            <input type="datetime" class="campo_hora" id='data_hora' name="data_hora" value="2019-05-{{$dia}} {{$horario}}:00"/>
                                        </td>
                                        <td class='id_td'>
                                            <input type='text' id='id_monitorado' name='id_monitorado' class='id_mon form-control' placeholder='ID'/>
                                        </td>
                                        <td class='nome_td'>
                                            <input class='nome_input form-control' id="nome_monitorado" name='nome_monitorado' placeholder='Nome monitorado' type="text">
                                        </td>
                                        <td class='motivo_td'>
                                            <select class='motivo_agendamento form-control' name='motivo'>
                                                <option disabled selected>Selecione uma opção...</option>
                                                <option value='Tornozeleira rompida'>Tornozeleira rompida</option>
                                                <option value='Aparelho não liga'>Aparelho não liga</option>
                                                <option value='Troca de carregador'>Troca de carregador</option>
                                                <option value='Sem comunicação'>Sem comunicação</option>
                                                <option value='Sem sinal GPS'>Sem sinal GPS</option>
                                                <option value='Não segura carga'>Não segura carga</option>
                                                <option value='Reativação'>Reativação</option>
                                                <option value='Devolução'>Devolução</option>
                                                <option value='Pendente'>Pendente</option>
                                            </select>
                                        </td>
                                        <td class='materiais_td'>
                                            <input type="checkbox" class='form-control' name="ct" value="x">
                                        </td>
                                        <td class='materiais_td'>
                                            <input type="checkbox" class='form-control' name="c" value="x">
                                        </td>
                                        <td class='materiais_td'>
                                            <input type="checkbox" class='form-control' name="t" value="x">
                                        </td>
                                        <td class='compareceu_td'>
                                            <select class='compareceu form-control' name='compareceu'>
                                                <option value='' selected>-</option>
                                                <option value='sim'>Sim</option>
                                                <option value='nao'>Não</option>
                                                <option value='reagendou'>Reagendou</option>
                                            </select>
                                        </td>
                                        <td class='acao_agend'>
                                            <button type="submit" onclick="actions('btn1', {{$dia}}, {{$horario}}, {{$qtd}})" class='position action add'>
                                                <span class='glyphicon glyphicon-plus'></span>
                                            </button>
                                                                                        
                                            <a class='position action delete'>
                                                <span class='glyphicon glyphicon-trash'></span>
                                            </a>
                                            
                                            <a class='position action edit'>
                                                <span class='glyphicon glyphicon-ok'></span>
                                            </a>
                                        </td>
                                    </tr>
                                </form>
                            @endif
                        @endfor
                    @endfor
                @endif <!-- Fim IF $diaFeriado-->         
		@endfor
	@else
        <h1> Erro ao carregar pagina</h1>        
	@endif
    </tbody>
</table>
@endsection