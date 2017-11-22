/** funzione per estrarre i valori dalla select */
function aggiornaHidden(sel) {
	var f = document.ordini;
	var val = eval('f.'+ sel.name +'_value');
	var txt = eval('f.'+ sel.name +'_text');
	if (val && txt) {
		val.value = sel.options[sel.selectedIndex].value;
		txt.value = sel.options[sel.selectedIndex].text;
	}
}

/** funzione per controllare e inviare i dati selezionati */
function conferma() {
	var ordine = document.getElementById("ordine").value;
	var tavolo = document.getElementById("tavolo").value;
	var ok = true;
	if (ordine == "") {
		alert ("Ordine non selezionato!!!!");
		ok = false;
	}
	if (tavolo == "") {
		alert ("Tavolo non selezionato!!!!");
		ok = false;
	}
	if (ok) {
		$.post("./aggiorna_ordine.php", {
			ordine: ordine,
			tavolo: tavolo
		},
		function(data, status) {
			$("#divrisposta").html(data);
		});
		setTimeout(function () {
			window.location.reload();
		}, 5000);
	}
}

/** grafica da select per i 3 selettori della pagina abbina.php */
$(document).ready(function() {
	$(".js-placeholder-ordine-hide-search").select2( {
		placeholder: "Seleziona ordine",
		allowClear: true,
		minimumResultsForSearch: Infinity
	});
});

$(document).ready(function() {
	$(".js-placeholder-gruppo-hide-search").select2( {
		placeholder: "Seleziona gruppo tavoli",
		allowClear: true,
		minimumResultsForSearch: Infinity
	});
});

$(document).ready(function() {
	$(".js-placeholder-tavolo-hide-search").select2( {
		placeholder: "Seleziona tavolo",
		allowClear: true,
		minimumResultsForSearch: Infinity
	});
});

/** funzione per passare i dati delle select a accoppia.php */
$(document).ready(function() {
	abbinamento();
	$("select").change(function() {
		abbinamento();
	})
});

function abbinamento() {
	$.ajax({
		type: "POST",
		url: "accoppia.php",
		data: $("#ricerca").serialize(),
		success: function(response) {
			eval(response);
		}
	});
}


