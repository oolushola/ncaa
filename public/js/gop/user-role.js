
$(function(){
    $(".userRole").click(function(){
        $value = $(this).attr("value");
        $("#role").val($value);
    });

    /**
     * @adduserole {Event}
     * @data {string}
     */

     $("#addRole").click(function(e){
        $("#loader").removeClass('error').html('');
        e.preventDefault();
         
        $fullName = $('#fullName').val();
        if($fullName === "") {
            $("#loader").html("Full Name is required.").addClass('error');
            return false
        }
        $email = $('#email').val();
        if($email === "") {
            $("#loader").html("Email is required.").addClass('error');
            return false
        }
        else{
            if(validateEmail($email) === false) {
                $("#loader").html("Only valid email are required.").addClass('error');
                return false;
            }
        }
        $phoneNo = $('#phoneNo').val();
        if($phoneNo === "") {
            $("#loader").html("Phone number is required.").addClass('error');
            return false
        }
        else{
            if($phoneNo.length < 11) {
                $("#loader").html("minimum of 11 characters are allowed. ").addClass('error');
                return false
            }
        }
        $password = $('#password').val();
        if($password === "") {
            $("#loader").html("Password is required.").addClass('error');
            return false
        }
        else{
            if($password.length < 6) {
                $("#loader").html("minimum of 6 characters are allowed. ").addClass('error');
                return false
            }
        }
        $role = $("#role").val();
        if($role == ""){
            $("#loader").html('User role is required').addClass('error');
            return false;
        }
        $("#loader").html("<img src='/images/ajax.gif'>&nbsp;Please Wait...").addClass('error');
        $.post('/user', $('#frmUser').serializeArray(), function(data) {
            if(data == 'exists'){
                $("#loader").html("A user with this email already exists.").addClass('error');
                return false
            }
            else{
                if(data === 'saved') {
                    $("#loader").html("New user added successfully.").addClass('error');
                    window.location='/user-role';
                }
            }
        })
     })

    function validateEmail(email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if( !emailReg.test( email ) ) {
            return false;
        }
        else {
            return true;
        }
    }
    
    $("#updateRole").click(function(e){
        $("#loader").removeClass('error').html('');
        e.preventDefault();
         
        $fullName = $('#fullName').val();
        if($fullName === "") {
            $("#loader").html("Full Name is required.").addClass('error');
            return false
        }
        $email = $('#email').val();
        if($email === "") {
            $("#loader").html("Email is required.").addClass('error');
            return false
        }
        else{
            if(validateEmail($email) === false) {
                $("#loader").html("Only valid email are required.").addClass('error');
                return false;
            }
        }
        $phoneNo = $('#phoneNo').val();
        if($phoneNo === "") {
            $("#loader").html("Phone number is required.").addClass('error');
            return false
        }
        else{
            if($phoneNo.length < 11) {
                $("#loader").html("minimum of 11 characters are allowed. ").addClass('error');
                return false
            }
        }
        $role = $("#role").val();
        if($role == ""){
            $("#loader").html('User role is required').addClass('error');
            return false;
        }
        $id = $('#user_id').val()
        $("#loader").html("<img src='/images/ajax.gif'>&nbsp;Please Wait...").addClass('error');

        $.post('/user/'+$id, $('#frmUser').serializeArray(), function(data) {
            if(data == 'exists'){
                $("#loader").html("A user with this email already exists.").addClass('error');
                return false
            }
            else{
                if(data === 'updated') {
                    $("#loader").html("New user added successfully.").addClass('error');
                    window.location='/user-role';
                }
            }
        })
     })

     $('.accessDeny').click(function() {
        $.get('/deny-user-access', { id: $(this).attr('id')}, function(data) {
            if(data === 'accessUpdated') {
                window.location = '/user-role'
            }
            else{
                return false
            }
        })
     })

})