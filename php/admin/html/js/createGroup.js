$().ready(function(){

	document.getElementById("createGroupBtn").disabled = true;
	document.getElementById("addUserBtn").disabled = true;

	$("#groupName").blur(function(){
		document.getElementById("createGroupBtn").disabled = false;
	});

	$("#srchUser").autocomplete({
		source: 'admin.php?action=srchUser',
		select: function( event, ui )
				{
					event.preventDefault();

					$("#srchUser").val(ui.item.label);
					$("#srchUser").attr("name", "selUser" + ui.item.value);
					
					if(document.getElementById("addUserBtn").disabled)
						document.getElementById("addUserBtn").disabled = false;
				}
	});

	$("#addUserBtn").click(function(){
		var validUser = true;
		if($("#srchUser").val() == ''){
			alert("User field cannot be blank");
			validUser = false;
		}

		if(validUser) {
			$(".userrow input").each(function(){
				if($(this).attr("name") == $("#srchUser").attr("name"))
				{
					alert("User is already added.");
					validUser = false;
				}
			});
		}

		if(validUser)
			$("#users").append(
					"<div class='userrow'>"+
						"<input type='text' name='"+ $("#srchUser").attr("name") + "' readonly value='" + $("#srchUser").val() + "' />" +
						"<button class=\"removeBtn\">Remove</button>" +
					"</div>"
			); 
		
		clearSrchUser();
		return false;			// to avoid form getting submitted.
	});

	$("#cancelGroupBtn").click(function(){
		document.getElementById("createGroupBtn").disabled = true;
		document.getElementById("addUserBtn").disabled = true;
		$("#groupName").val('');
		clearSrchUser();
		$("#users").children(".userrow").remove();

		return false;			// to avoid form getting submitted.
	});

	$("#createGroupBtn").click(function(){
		clearSrchUser();
	});

	$("#users").on('click', '.removeBtn', function(){
		$(this).parent().remove();
		return false;			// to avoid form getting submitted.
	});
});

function clearSrchUser(){
	$("#srchUser").val('');					// resetting search user field
	$("#srchUser").removeAttr("name");		// removing name attribute from search user field to avoid passing to form submission.	
}