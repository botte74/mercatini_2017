function aggiornaTabellaDistribuzione() {
	var url = "./tabella_distribuzione.php"; // the script where you handle the form input.
	$.get(url,function(response) {
		$('#tabella').html(response);
	},"html");
}

function aggiornaTabellaOrdini() {
	var url = "./tabella_ordini_distribuzione.php"; // the script where you handle the form input.
	$.get(url,function(response) {
		$('#tabella_ordini').html(response);
	},"html");
}

function aggiornaTabellaOrdiniDaStampare() {
	var url = "./tabella_ordini_da_stampare.php"; // the script where you handle the form input.
	$.get(url,function(response) {
		$('#tabella_ordini_stampare').html(response);
	},"html");
}

function aggiornaTabellaChiamataGriglie() {
	var url = "./tabella_chiamata_griglie.php"; // the script where you handle the form input.
	$.get(url,function(response) {
		$('#tabella_chiamata').html(response);
	},"html");
}

$(document).ready(function() {
	// imposto focus sul nome cliente
	document.getElementById("barcode").focus();
	
	$("#barcode").keypress(function(ev) {

		// se premuto invio sulla cella
		if(ev.which == 13) {
			chiudi_ordine();
			return false;
		}
	});
});

function conferma() {
	chiudi_ordine();
}

// chiama chiusura ordine e visualizza lo stato
function chiudi_ordine() {
	var barcode = document.getElementById("barcode").value;//by id
	var url = "./chiusura_ordine.php?barcode="+barcode;
	$.get(url,function(response) {
		$('#stato').html(response);
		aggiornaTabellaOrdini();
		aggiornaTabellaOrdiniDaStampare();
	},"html");
	document.getElementById("barcode").value = '';
	document.getElementById("barcode").focus();
}

function ristampa(ordine,gruppo) {
	var url = "./stampa_distribuzione_sagra.php?ordine="+ordine+"&gruppo="+gruppo; // the script where you handle the form input.
	$.get(url,function(response) {
		$('#stato').html(response);
	},"html");
	document.getElementById("barcode").value = '';
	document.getElementById("barcode").focus();
}

function ristampa_speciale(ordine,gruppo) {
	var url = "./stampa_distribuzione_sagra.php?ordine="+ordine+"&gruppo="+gruppo+"&speciale=S"; // the script where you handle the form input.
	$.get(url,function(response) {
		$('#stato').html(response);
		aggiornaTabellaOrdini();
		aggiornaTabellaOrdiniDaStampare();
	},"html");
	document.getElementById("barcode").value = '';
	document.getElementById("barcode").focus();
}

function richiesta(prodotto) {
	var url = "./aggiorna_griglie.php?richiesta="+prodotto; // the script where you handle the form input.
	$.get(url,function(response) {
		$('#stato').html(response);
		aggiornaTabellaChiamataGriglie();
	},"html");
	document.getElementById("barcode").value = '';
	document.getElementById("barcode").focus();
}
