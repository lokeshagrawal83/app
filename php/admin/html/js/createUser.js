$().ready(function(){

	$("#addUser").click(function(event){
	
		event.preventDefault();
		$("#myTable").append('<tr><td><input name="username[]" /></td><td><input type="password" name="password[]" /></td></tr>');
	});
});
