function render() {
	var signinParams = {
		'callback' : signinCallback,
		'data-redirecturi' : "postmessage",
		'data-accesstype' : "offline",
		'approval': "auto"
	};
	var signupParams = {
		'callback' : signupCallback
	};

	// Attach a click listener to a button to trigger the flow.
	$("#signin-button-div").click(function() {
		//alert("signin");
		gapi.auth.signIn(signinParams);
		// Will use page level configuration
	});

	$("#signup-button-div").click(function() {
		//alert("signup");
		gapi.auth.signIn(signupParams);
		// Will use page level configuration
	});

}

var signupclass = (function() {

	//Defining Class Variables here
	var response = undefined;
	return {
		//Class functions / Objects

		mycoddeSignIn : function(response) {
			// The user is signed in
			if (response['access_token']) {

				//Get User Info from Google Plus API
				gapi.client.load('plus', 'v1', this.getUserInformation);

			} else if (response['error']) {
				// There was an error, which means the user is not signed in.
				//alert('There was an error: ' + authResult['error']);
			}
		},

		getUserInformation : function() {
			var request = gapi.client.plus.people.get({
				'userId' : 'me'
			});
			request.execute(function(profile) {
				var email = profile['emails'].filter(function(v) {
				return v.type === 'account'; // Filter out the primary email
				})[0].value;
				var fName = profile.displayName;

				sendUserInfo(fName, email);
			});
		}
	};
	//End of Return
})();

var signinclass = (function() {

	//Defining Class Variables here
	var response = undefined;
	return {
		//Class functions / Objects

		mycoddeSignIn : function(response) {
			// The user is signed in
			if (response['code']) {
				window.location = "user-check.php?code=" + response['code'];
				// location.href = "user-check.php?code=" + response['code'];
				console.log("Inside sign in");

			} else if (response['error']) {
				// There was an error, which means the user is not signed in.
				//alert('There was an error: ' + authResult['error']);
			}
		},

		getUserInformation : function() {
			var request = gapi.client.plus.people.get({
				'userId' : 'me'
			});
			request.execute(function(profile) {
				var email = profile['emails'].filter(function(v) {
				return v.type === 'account'; // Filter out the primary email
				})[0].value;
				var fName = profile.displayName;
				callhomepage(fName, email);
			});
		}
	};
	//End of Return
})();
function signupCallback(gpSignInResponse) {

	// Respond to signin, see https://developers.google.com/+/web/signin/
	signupclass.mycoddeSignIn(gpSignInResponse);
}

function signinCallback(gpSignInResponse) {

	// Respond to signin, see https://developers.google.com/+/web/signin/
	signinclass.mycoddeSignIn(gpSignInResponse);
}

function callhomepage(fName, email) {

	window.location = "user-check.php?email=" + email;
}

function sendUserInfo(fName, email) {

	window.location = "child-signup.php?fname=" + fName + "&email=" + email;
}

function logout(){
	gapi.auth.signOut();
}