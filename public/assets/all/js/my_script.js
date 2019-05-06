/* ---------- FUNÇOES  ----------- */

/* -- LINK -- */
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


/* -- AÇÃO -- */

var inputEle = document.getElementsByClassName('id_mon');

for(i = 0; i < inputEle.length; i++){
	inputEle[i].addEventListener('keyup', function(e){
		var key = e.which || e.keyCode;
		if(key.length > 2){
			var i;
			for(i = 0; i < $lista_key_monitorado.length; i++)
			{
				if(this.value == $lista_key_monitorado[i]){
					console.log('ID: ', this.value);
					console.log('Nome: ', $lista_nome_monitorado[i]);
				}
			}
			console.log('carregou enter o valor digitado foi: ', this.value);
		}
	});
}