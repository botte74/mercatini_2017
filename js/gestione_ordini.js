function aggiornaordine() {
	var validForm = true;
	// esegui controlli sugli input del form ed eventualmente setti validForm=false
	if(validForm) {
		var myFormData = $("#form-ordine").serialize();
		//alert($('#ordine').val());
		//alert('ciao');
		// ajax asincrono, la pagina non si blocca in attesa del risultato della pagina caricata
		$.ajax({
			type: "POST",
			url: "aggiorna_ordine.php",
			data: myFormData,
			//data: {
			//	ordine: $('#ordine').val(),
			//	nome: $('#nome').val(),
			//	tavolo: $('#tavolo').val()
			//},
			//dataType: "json",
			success: function(returnedData) {
				//metto nel div divRitorno i valori ritornati dalla pagina esterna.php
				$("#divritorno").html(returnedData);
			},
			error: function(){
				alert('errore in esecuzione ajax');
			}
		});
		document.getElementById("nota").value = '';
	}
}

$(document).ready(function() {
	// imposto focus sul nome cliente
	document.getElementById("nome").focus();
	//alert('jackpronto');
	$("#nome").keypress(function(ev) {
		//alert('jack');
		if(ev.which == 13) {
		aggiornaordine();
		return false;
		}
	});

	$("#nome").change(function() {
		aggiornaordine();
	});
	
	$("#coperti").change(function() {
		aggiornaordine();
	});
	
	$("#tipo").change(function() {
		aggiornaordine();
	});
	
	$("#nota").keypress(function(ev) {
		//alert('jack');
		if(ev.which == 13) {
			//alert('nota');
			aggiornanota();
			return false;
		}
	});
	
	var deviceAgent = navigator.userAgent.toLowerCase();
	//var isTouchDevice = false;
	if 
		(deviceAgent.match(/(iphone|ipod|ipad)/) ||
		deviceAgent.match(/(android)/)  || 
		deviceAgent.match(/(iemobile)/) || 
		deviceAgent.match(/iphone/i) || 
		deviceAgent.match(/ipad/i) || 
		deviceAgent.match(/ipod/i) || 
		deviceAgent.match(/blackberry/i) || 
		deviceAgent.match(/bada/i)) {
			isTouchDevice = true;
		}
});

var rigaSelezionata = 0;
var isTouchDevice = false;
	
function aggiornaRighe(ordine) {
	var url = "./lista_righe_ordine.php?ordine="+ordine; // the script where you handle the form input.
	$.get(url,function(response) {
		//alert(response);
		//$("#output").append(response);
		var res = response.split("|");
		$('#tabellarighe').html(res[0]);
		$('#prezzo').html(res[1]);
	},"html");
	document.getElementById("nota").value = '';
}

function piuUno(ordine,riga) {
	var url = "./righe_ordine.php?tipo=piu&ordine="+ordine+"&riga="+riga; // the script where you handle the form input.
	//alert(ordine + " " + riga);
	$.get(url,function(response) {
		//alert(response);
		//$("#output").append(response);
		//$('#tabella tr:last').after(response);
		$('#divstato').html(response);
		aggiornaRighe(ordine);
	},"html");
	document.getElementById("nota").value = '';
}

function menoUno(ordine,riga) {
	var url = "./righe_ordine.php?tipo=meno&ordine="+ordine+"&riga="+riga; // the script where you handle the form input.
	//alert(ordine + " " + riga);
	$.get(url,function(response) {
		//alert(response);
		//$("#output").append(response);
		//$('#tabella tr:last').after(response);
		$('#divstato').html(response);
		aggiornaRighe(ordine);
	},"html");
	document.getElementById("nota").value = '';
}
	
function cancella(ordine,riga) {
	var url = "./righe_ordine.php?tipo=cancella&ordine="+ordine+"&riga="+riga; // the script where you handle the form input.
	//alert(ordine + " " + riga);
	$.get(url,function(response) {
		//alert(response);
		//$("#output").append(response);
		//$('#tabella tr:last').after(response);
		$('#divstato').html(response);
		aggiornaRighe(ordine);
	},"html");
	document.getElementById("nota").value = '';
}
	
function faiOrdine(ordine,codice) {
	var url = "./righe_ordine.php?tipo=nuova&ordine="+ordine+"&codice="+codice; // the script where you handle the form input.
	var ultimariga = 0;
	$.get(url,function(response) {
		//alert(response);
		//$("#output").append(response);
		//$('#tabella tr:last').after(response);
		var res = response.split("|");
		ultimariga = res[1];
		rigaSelezionata = ultimariga;
		$('#divstato').html(res[0]);
		aggiornaRighe(ordine);
	},"html");
	document.getElementById("nota").value = '';
	// imposto focus sul nome cliente
	if (! (isTouchDevice)) {
		document.getElementById("nota").focus();
	}

}
	
function aggiunta(ordine, codice) {
	if (rigaSelezionata == 0) {
		$('#divstato').html("Nessuna Riga Selezionata!!")
	} else {
		var url = "./righe_ordine.php?tipo=aggiunta&ordine="+ordine+"&codice="+codice+"&riga="+rigaSelezionata; // the script where you handle the form input.
		$.get(url,function(response) {
			//alert(response);
			//$("#output").append(response);
			//$('#tabella tr:last').after(response);
			$('#divstato').html(response);
			aggiornaRighe(ordine);
		},"html");
		document.getElementById("nota").value = '';
	}
}
	
function selezione(ordine, riga) {
	// rimette il colore sulla vecchia riga
	$('#rigatab'+rigaSelezionata).css('background-color', 'rgba(255,255,255,0)');
	$('#rigatabnota'+rigaSelezionata).css('background-color', 'rgba(255,255,255,0)');
	rigaSelezionata = riga;
	// coloro di blu la nuova riga
	$('#rigatab'+rigaSelezionata).css('background-color', 'yellow');
	$('#rigatabnota'+rigaSelezionata).css('background-color', 'yellow');
	$('#divstato').html("Riga selezionata: " + rigaSelezionata);
	// imposto focus sul nome cliente
	document.getElementById("nota").focus();
}
	
function selezionanota(ordine, riga) {
	// rimette il colore sulla vecchia riga
	$('#rigatab'+rigaSelezionata).css('background-color', 'rgba(255,255,255,0)');
	$('#rigatabnota'+rigaSelezionata).css('background-color', 'rgba(255,255,255,0)');
	rigaSelezionata = riga;
	// coloro di blu la nuova riga
	$('#rigatab'+rigaSelezionata).css('background-color', 'yellow');
	$('#rigatabnota'+rigaSelezionata).css('background-color', 'yellow');
	$('#divstato').html("Riga selezionata: " + rigaSelezionata);
	var idnota = "testonota"+riga;
	//alert (idnota);
	var nota = document.getElementById(idnota).textContent;
	//alert (nota);
	document.getElementById("nota").value = nota;
	// imposto focus sul nome cliente
	document.getElementById("nota").focus();
}
	
function OrdineGratis(ordine) {
	var url = "./righe_ordine.php?tipo=gratis&ordine="+ordine; // the script where you handle the form input.
	var ultimariga = 0;
	$.get(url,function(response) {
		//alert(response);
		//$("#output").append(response);
		//$('#tabella tr:last').after(response);
		//var res = response.split("|");
		//ultimariga = res[1];
		//rigaSelezionata = ultimariga;
		//$('#divstato').html(res[0]);
		$('#divstato').html(response);
		aggiornaRighe(ordine);
	},"html");
	document.getElementById("nota").value = '';
}
	
function aggiornanota() {
	if (rigaSelezionata == 0) {
		$('#divstato').html("Nessuna Riga Selezionata!!")
	} else {
		//alert("ciao");
		var nota = document.getElementById("nota").value;//by id
		//alert (nota);
		var ordine = document.getElementById("nrordine").value;//by id
		//alert(ordine);
		var riga = rigaSelezionata;
		var url = "./righe_ordine.php?tipo=nota&ordine="+ordine+"&riga="+riga+"&nota="+nota; // the script where you handle the form input.
		//alert(ordine + " " + riga);
		$.get(url,function(response) {
			//alert(response);
			//$("#output").append(response);
			//$('#tabella tr:last').after(response);
			$('#divstato').html(response);
			aggiornaRighe(ordine);
		},"html");
		document.getElementById("nota").value = '';
	}
}

function eliminanota(ordine,riga) {
	var url = "./righe_ordine.php?tipo=eliminanota&ordine="+ordine+"&riga="+riga; // the script where you handle the form input.
	//alert(ordine + " " + riga);
	$.get(url,function(response) {
		$('#divstato').html(response);
		aggiornaRighe(ordine);
	},"html");
	document.getElementById("nota").value = '';
}