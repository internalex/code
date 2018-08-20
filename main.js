function copycontent(){
	for (var i = 0; i < 3; i++){
		var piece = ["title", "content", "tags"][i];
		var editzone = document.getElementById(piece);
		var input = document.getElementById(piece + "-input");				
		input.value = editzone.innerHTML;	
	}		

}

function search() {
	window.location.href = "search.php?search=" + document.getElementById("searchbox").value;
}
