$(function(){

    function validateEmail(email) {
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		if( !emailReg.test( email ) ) {
			return false;
		}
		else {
			return true;
		}
	}

    $("#updateUserProfile").click(function(e){
        alert('Ok')
        e.preventDefault();
        $file = $("#file").val();
        if($file != ''){
            var ftype = $("#ftype").val();
            validateFile(ftype);
        }
        $phone = $("#phone").val();
        $email = $("#email").val();
        if($email == ''){
            $("#loader").html('Email is required').addClass('error');
            return;
        }
        else{
            if(validateEmail($email)==false){
                $("#loader").html('Only valid email is allowed.').addClass('error');
                return
            }
        }
        $("#loader").html("<img src='/images/ajax.gif'>Please Wait...").addClass('error');
        $("#frmProfileUpdate").submit();
    });

    $("#frmProfileUpdate").ajaxForm(function(data){
        if(data == "updated"){
            window.location="/update-profile";
        }
    })

    
});



