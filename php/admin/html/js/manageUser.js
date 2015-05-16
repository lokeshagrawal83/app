$().ready(function(){

	document.getElementById("editGroupBtn").disabled = true;
	document.getElementById("addUserBtn").disabled = true;

	$("#groupName").change(function(){
		document.getElementById("editGroupBtn").disabled = false;
	});

	$("#srchUser").autocomplete({
		source: 'admin.php?action=srchUser',
		select: function( event, ui )
				{
					event.preventDefault();

					$("#srchUser").val(ui.item.label);
					$("#userId").val(ui.item.value);
					
					if(document.getElementById("addUserBtn").disabled)
						document.getElementById("addUserBtn").disabled = false;
				}
	});

});
