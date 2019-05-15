<!DOCTYPE html>
<html>
	<head>
		<title>@yield('title')</title>
		<!--Bootstrap-->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
		<link rel="stylesheet" href="{{url('assets/all/css/style.css')}}">
		<link rel="stylesheet" href="{{url('assets/all/css/index.css')}}">
		<script src="{{url('assets/all/js/my_script.js')}}"></script>
			
	</head>
	<body>
		<nav id="opcoes">
			<ul>
				<li><a href="{{ url('/') }}">Home</a></li>
				<li><a href="{{ url('/lista_monitorados') }}">Cadastrar Monitorado</a></li>
				<li><a href="{{ url('/janeiro') }}">Janeiro</a></li>
				<li><a href="{{ url('/fevereiro') }}">Fevereiro</a></li>
				<li><a href="{{ url('/marco') }}">Março</a></li>
				<li><a href="{{ url('/abril') }}">Abril</a></li>
				<li><a href="{{ url('/maio') }}">Maio</a></li>
				<li><a href="{{ url('/junho') }}">Junho</a></li>
				<li><a href="{{ url('/julho') }}">Julho</a></li>
				<li><a href="{{ url('/agosto') }}">Agosto</a></li>
				<li><a href="{{ url('/setembro') }}">Setembro</a></li>
				<li><a href="{{ url('/outubro') }}">Outubro</a></li>
				<li><a href="{{ url('/novembro') }}">Novembro</a></li>
				<li><a href="{{ url('/dezembro') }}">Dezembro</a></li>
				<li><a href="{{ url('/relatorio_teste') }}">Relatório</a></li>
			</ul>
		</nav>
		<div class="container">
			@yield('content')
		</div>
	</body>
</html>