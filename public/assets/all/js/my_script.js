function actions(valor, dia, horario, qtd )
{
  var form1 = "mail_form" + dia + horario + qtd;
	if(valor == "btn1"){
		document.getElementById(form1).action = '/cadastro_agendamento';
		document.getElementById(form1).submit();
	}
	if(valor == "btn2"){
		document.getElementById(form1).action = '/cadastro_manutencao';
		document.getElementById(form1).submit();
	}
 }
