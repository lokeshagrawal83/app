function initTinyMCE(sendLogs)
{
	tinymce.init({
				selector: "textarea",
			    theme: "modern",
			    menubar: false,
			    statusbar: false,
		//	    forced_root_block: false,
				auto_focus: 'textarea',
			    width: 500,
			    height: 100,
			    setup : function(ed) {
				      ed.on('keyup', function(e) {
				    //    	tinymce.triggerSave();		
				          	var keycode = e.which;		          
				          	if (keycode == 13)
						  	{							  		
						  		if(ed.getContent().indexOf('<p>&nbsp;</p>') == 0){
						  			alert('Message cannot be empty !');		
						  			ed.setContent('');
						  			ed.selection.setCursorLocation(0,0);							
						  		} else {
						  			submitChat();
						  		}						  		
						  	} else {
						  		if (sendLogs == 0) {
						  			sendMessage(1);
						  		}
						  	}
				      	});
				},
			    plugins: [
			         "hr anchor pagebreak spellchecker",
			         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
			         "emoticons template paste textcolor"
			   ],
			   content_css: "css/content.css",
			   toolbar: " styleselect | bold italic | forecolor backcolor emoticons", 
			   style_formats: [
			        {title: 'Bold text', inline: 'b'},
			        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
			        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
			        {title: 'Example 1', inline: 'span', classes: 'example1'},
			        {title: 'Example 2', inline: 'span', classes: 'example2'},
			        {title: 'Table styles'},
			        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
			    ]
			});
}

function submitChat()
{	
	tinymce.get('textarea').focus();
	if (tinymce.get('textarea').getContent() == '') 
	{
		alert('Message cannot be empty !');
		return false ;
	}

	sendMessage();
	tinymce.get('textarea').setContent('');
	tinymce.get('textarea').selection.setCursorLocation(0,0);
	return false; 
}

function sendMessage(mode)
{
	if (tinymce.get('textarea').getContent() != '') 
	{
		var message = escape(tinymce.get('textarea').getContent()); // chatForm.message.value;

//		alert(message);

		var xmlhttp = new XMLHttpRequest();

		xmlhttp.onreadystatechange = function()
		{
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
			{
				var elem = document.getElementById('area');
				elem.innerHTML = xmlhttp.responseText;
				elem.scrollTop = elem.scrollHeight;
			}
		};

		if(mode == 1)
			xmlhttp.open('GET','chat.php?action=update&msg='+message, true);
		else	
			xmlhttp.open('GET','chat.php?action=insert&msg='+message, true);

		xmlhttp.send();
	}
}

$().ready(function(){

		initTinyMCE($('#sendLogs').val());

		$.ajaxSetup({ cache:false });

		setInterval(
			function(){
				$('#area').load('chat.php?action=logs', function(){
					var elem = document.getElementById('area');
					elem.scrollTop = elem.scrollHeight;
				});
			}, 
			2000
		);
	}
);