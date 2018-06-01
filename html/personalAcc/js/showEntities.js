function showForm() {
	var f = document.getElementById("pdfGenForm");
	
	if (f.style.display === "none") {
		f.style.display = "block";
	} else {
		f.style.display = "none";
	}
}
		
function showAction(action) {
	var f = document.getElementById("pdfGenForm");
	f.style.display = "none";

	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function () {
		if(this.readyState == 4 && this.status == 200) {
			document.getElementById("actionField").innerHTML = this.responseText;
		}
	};
	
	xhttp.open("GET", action, true);
	xhttp.send();
}