$().ready(function(){

	if (document.getElementById("editGroupBtn")) {
		document.getElementById("editGroupBtn").disabled = true;
		document.getElementById("addUserBtn").disabled = true;
	}

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

	checkIdentity();

	$("#identity").click(function(){

		checkIdentity();
	});

	$("#gIdentity").click(function(){

		checkIdentity();
	});
});


function checkIdentity(){

	if ($("#identity").prop('checked')) {
		$("#genericIdentity").hide();
		$("#profile").show();
		$("#pic").show();
		$("#allowUsers").show();
		$("#param1").show();
		$("#param2").show();
		$("#param3").show();
	} else {
		$("#genericIdentity").show();
		$("#profile").hide();
		$("#pic").hide();
		$("#param1").hide();
		$("#param2").hide();
		$("#param3").hide();
		$("#allowUsers").hide();
	}

	if ($("#gIdentity").prop('checked')) {
		$("#showIdentity").hide();
		$("#groupPic").show();
		$("#browsePic").show();
	} else {
		$("#showIdentity").show();
		$("#groupPic").hide();
		$("#browsePic").hide();
	}
}