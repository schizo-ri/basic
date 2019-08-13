function GO_value(){
	if(document.getElementById("prikaz").value == "GO" ){
		document.getElementById("napomena").value = "GO" ;
		document.getElementById("zahtjev").innerHTML = "Zahtjev";
	}else {
		document.getElementById("napomena").value = "" ;
	}
	if(document.getElementById("prikaz").value == "Bolovanje" ){
		document.getElementById("zahtjev").innerHTML = "Obavijest";
	}
	if(document.getElementById("prikaz").value == "Izlazak"){
		document.getElementById("zahtjev").innerHTML = "Zahtjev";
	}
	if(document.getElementById("prikaz").value == "NPL" ||document.getElementById("prikaz").value == "PL"){
		document.getElementById("zahtjev").innerHTML = "Zahtjev";
	}
	if(document.getElementById("prikaz").value == "SLD"){
		document.getElementById("zahtjev").innerHTML = "Zahtjev";
	}
	if(document.getElementById("prikaz").value == "Vik"){
		document.getElementById("zahtjev").innerHTML = "Zahtjev";
	}
	
}