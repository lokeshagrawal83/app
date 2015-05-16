$().ready(function(){

//	checkEmail();

//	$("#email").keyup(function(){
//		checkEmail();
//	});
	
	$("#addHobby").click(function(event){
	
		event.preventDefault();
		$("#myTable").append('<tr><td/><td class="data"><input name="hobby[]" /></td></tr>');
	});
});

function checkEmail(){
	if($('#email').val() == '')
		document.getElementById("nextBtn").disabled = true;
	else
		document.getElementById("nextBtn").disabled = false;
}