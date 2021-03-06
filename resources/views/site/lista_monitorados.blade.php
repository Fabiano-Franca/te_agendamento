@extends('site.template.template_index')

@section('title', 'Janeiro')

@section('content')
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

@if(isset($monitorado))
<form action="{{ url('/update', $monitorado->id) }}" method="post">
@else
<form action="{{ url('/cadastro') }}" method="post">
@endif
	{!! csrf_field() !!}
	

	<!-- DADOS PESSOAIS-->
	<fieldset>
		<legend>Cadastrar Monitorado</legend>
		<div class="form-group">
			<label for="id_monitorado">ID</label>
			<input type="number" name="id_monitorado" class="form-control" id="id_monitorado" placeholder="ID do monitorado" value="{{ (isset($monitorado) ? $monitorado->id_monitorado : old('id_monitorado')) }}">
		</div>
		<div class="form-group">
			<label for="nome">Monitorado</label>
			<input type="name" name="nome" class="form-control" id="nome" placeholder="Nome do monitorado" value="{{ (isset($monitorado) ? $monitorado->nome : old('nome')) }}">
		</div>
		<button class="btn btn-primary">Cadastrar</button>
	</fieldset>
</form>

<!-- LISTAGEM DOS MONITORADOS -->

<h1 class="title-pg">Lista de Monitorados</h1>
<table class="table table-striped">
	<tr class="tr_lista_monitorados">
		<th>ID</th>
		<th>NOME</th>
		<th>AÇÕES</th>
	</tr>
	
	@foreach($monitorados as $monitorado)

	<tr>
		<td>{{$monitorado->id_monitorado}}</td>
		<td>{{$monitorado->nome}}</td>
		<td>
			<a href="{{url('/edit_monitorado', $monitorado->id)}}" class="action edit">
				<span class="glyphicon glyphicon-pencil"></span>
			</a>
			<a href="{{url('/destroy', $monitorado->id)}}" class="action delete">
				<span class="glyphicon glyphicon-trash"></span>
			</a>
		</td>
	</tr>

	@endforeach

</table>

@endsection