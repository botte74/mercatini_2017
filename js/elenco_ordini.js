function aggiornaTabellaOrdini() {
	var url = "./tabella_elenco_ordini.php"; // the script where you handle the form input.
	$.get(url,function(response) {
		$('#tabella').html(response);
	},"html");
}

function ristampa(ordine) {
	var url = "./stampa_distribuzione_sagra.php?ordine="+ordine; // the script where you handle the form input.
	$.get(url,function(response) {
		$('#stato').html(response);
	},"html");
}