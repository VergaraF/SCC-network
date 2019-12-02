function validateSignupForm(){
	var username = document.signup.username;
	var password = document.signup.password;
	var confirmPass = document.signup.cPassword;
	var bod = document.signup.bod;
	if(validateUsername(username, 5)){
		if(checkPassword(password)){
			if(comparePasswords(password, confirmPass)){ 
				if(checkDateOfBirth(bod)){
					document.form('signup').submit();
				} 
			}
		}
    }
    
	return false;
}

//==================SIGN UP VALIDATIONS================================//
function validateUsername(inputTxt, len){
	if (inputTxt.value.length >= len) {
		return true;
    }
    
    alert('Username length should be ' + len + ' characters long at least');
	inputTxt.focus();
	return false;
}

function checkDateOfBirth(inputTxt)
{
    var date = /\b\d{4}[-. ]?[ ]?\d{2}[-. ]?\d{2}\b/;
    if((inputTxt.value.match(date))){
        return true;
    }

    alert("The format of the date is wrong. It should follow the format 'YYYY-MM-DD");
	inputTxt.focus();
	return false;
}

function checkPassword(inputTxt) 
{ 
	var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/;
	if(inputTxt.value.match(passw)) { 
		return true;
    }
    
    alert('The password should be between 6 to 20 characters and it should have at least 1 digit, 1 uppercase and 1 lowercase');
	inputTxt.focus();
	return false;
}

function comparePasswords(pass1, pass2){
	if(pass1.value.valueOf() === pass2.value.valueOf()){
		return true;
    }
    
    alert('Confirmation failed! The passwords you entered did not match. Please retype it!');
	pass2.focus();
    return false;
}