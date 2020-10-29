
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
         
         $userid = $("#userid").val();
            if($userid == '0'){
                $("#loader").html('A user is required').addClass('error');
                return false;
            }
        $role = $("#role").val();
            if($role == ""){
                $("#loader").html('User role is required').addClass('error');
                return false;
            }
        $("#loader").html("<img src='/images/ajax.gif'>&nbsp;Please Wait...").addClass('error');
        $.post('/user-role/'+$userid, $("#frmUserRole").serialize(), function(data){
            if(data == 'updated'){
                window.location='/user-role';
            }
        })
     })
})