function aggiornaTabellaGriglie() {
	var url = "./tabella_griglie.php"; // the script where you handle the form input.
	$.get(url,function(response) {
		$('#tabella').html(response);
	},"html");
}

function incrementa(prodotto) {
	var add=document.getElementById("aggiungi"+prodotto).value;
	var url = "./aggiorna_griglie.php?prodAdd="+prodotto+"&quantAdd=" + add; // the script where you handle the form input.
	$.get(url,function(response) {
		$('#ritorno').html(response);
		aggiornaTabellaGriglie();
	},"html");
}

function azzera(prodotto) {
	var url = "./aggiorna_griglie.php?prodDel="+prodotto; // the script where you handle the form input.
	$.get(url,function(response) {
		$('#ritorno').html(response);
		aggiornaTabellaGriglie();
	},"html");
}

function noProblem(prodotto) {
	var url = "./aggiorna_griglie.php?noProblem="+prodotto; // the script where you handle the form input.
	$.get(url,function(response) {
		$('#ritorno').html(response);
		aggiornaTabellaGriglie();
	},"html");
}

