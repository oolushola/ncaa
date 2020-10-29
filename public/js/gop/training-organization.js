$(function(){
    $("#addTrainingOrganization").click(function(e){
        e.preventDefault();
        $training_organization = $("#training_organization").val();
        if($training_organization == ""){
            $("#loader").html('Training organization is required.').addClass('error');
            return
        }
        $("#loader").html('<img src="/images/ajax.gif">Please wait...').addClass('error');
        $.post("/training-organization", $("#frmTrainingOrganization").serialize(), function(data){
            if(data == 'exist'){
                $("#loader").html(`<i class=\"mdi mdi-alert\"></i> ${$training_organization} already exists.`);
                return;
            }
            $url= '/training-organization';
            window.location = $url;
        });
    });

    $("#updateTrainingOrganization").click(function(e){
        e.preventDefault();
        $training_organization = $("#training_organization").val();
        if($training_organization == ""){
            $("#loader").html('Training organization is required.').addClass('error');
            return
        }
        $id = $("#id").val();
        $("#loader").html('<img src="/images/ajax.gif">Please wait...').addClass('error');
        $.post("/training-organization/"+$id, $("#frmTrainingOrganization").serialize(), function(data){
            if(data == 'exist'){
                $("#loader").html(`<i class=\"mdi mdi-alert\"></i> ${$training-organization} already exists.`);
                return;
            }
            $url= '/training-organization';
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
    $(".deleteTrainingOrganization").click(function(){
        $id = $(this).attr("value");
        $name = $(this).attr("title");
        $ask = confirm("Are you sure you want to delete "+$name.toLowerCase()+"?");
        if($ask){
            $("#deleteLoader")
            .html(
                '<img src=\'/images/ajax.gif\'>please wait...'
            )
            .addClass('error');
            $.post("/training-organization/"+$id, $("#frmDeleteTrainingOrganization").serialize(), function(data){
               if(data=='cant_delete'){
                   $("#deleteLoader").html("<i class='mdi mdi-alert'></i> Sorry, training organization is in use by a ATO Record. Won't Delete.").css({color:'green'});
                   return;
               }
               $url = '/training-organization';
               window.location = $url;
            })
        }
        return;
    });

})