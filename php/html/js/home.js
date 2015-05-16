$().ready(function(){

	/* Mixpanel Code */
	$.getJSON("home.php?action=user", function(data)
		{
			mixpanel.identify(data.userid);
			mixpanel.people.set({
				'User ID': data.userid,
				'Username': data.username,
				'Email': data.email,
				'First Name': data.firstname,
				'Last Name': data.lastname,
				'Gender': data.gender,
				'Age' : data.age
			});

			mixpanel.track("Page View", {"Name": "Home Page"});
		}
	);
});