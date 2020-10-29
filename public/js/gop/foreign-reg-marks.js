$(function(){
    $("#addForeignRegMarks").click(function(e){
        e.preventDefault();
        $foreign_registration_marks = $("#foreign_registration_marks").val();
        if($foreign_registration_marks == ""){
            $("#loader").html('Foreign Registration Marks is required.').addClass('error');
            return
        }
        $("#loader").html('<img src="/images/ajax.gif">Please wait...').addClass('error');
        $.post("/foreign-registration-marks", $("#frmForeignRegistrationMarks").serialize(), function(data){
            if(data == 'exist'){
                $("#loader").html(`<i class=\"mdi mdi-alert\"></i> ${$foreign_registration_marks} already exists.`);
                return;
            }
            $url= '/foreign-registration-marks';
            window.location = $url;
        });
    });

    $("#updateForeignRegMarks").click(function(e){
        e.preventDefault();
        $foreign_registration_marks = $("#foreign_registration_marks").val();
        if($foreign_registration_marks == ""){
            $("#loader").html('Foreign Registration Marks is required.').addClass('error');
            return
        }
        $id = $("#id").val();
        $("#loader").html('<img src="/images/ajax.gif">Please wait...').addClass('error');
        $.post("/foreign-registration-marks/"+$id, $("#frmForeignRegistrationMarks").serialize(), function(data){
            if(data == 'exist'){
                $("#loader").html(`<i class=\"mdi mdi-alert\"></i> ${$foreign_registration_marks} already exists.`);
                return;
            }
            $url= '/foreign-registration-marks';
            window.location = $url;
        });
    });

    /**
     * click delete event
     * 
     * @request aircraft type id
     * 
     * @return string
     */
    $(".deleteforeignregistrationmarks").click(function(){
        $id = $(this).attr("value");
        $name = $(this).attr("title");
        $ask = confirm("Are you sure you want to delete "+$name.toLowerCase()+"?");
        if($ask){
            $("#deleteLoader")
            .html(
                '<img src=\'/images/ajax.gif\'>please wait...'
            )
            .addClass('error');
            $.post("/foreign-registration-marks/"+$id, $("#frmDeleteforeignregistrationmarks").serialize(), function(data){
               if(data=='cant_delete'){
                   $("#deleteLoader").html("<i class='mdi mdi-alert'></i> Sorry, Foreign Registration Marks is in use by an FOCC. Won't Delete.").css({color:'green'});
                   return;
               }
               $url = '/foreign-registration-marks';
               window.location = $url;
            })
        }
        return;
    });

})