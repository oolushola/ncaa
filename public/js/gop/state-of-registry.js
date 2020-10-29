$(function(){
    $("#addStateOfRegistry").click(function(e){
        e.preventDefault();
        $state_of_registry = $("#state_of_registry").val();
        if($state_of_registry == ""){
            $("#loader").html('State of Registry is required.').addClass('error');
            return
        }
        $("#loader").html('<img src="/images/ajax.gif">Please wait...').addClass('error');
        $.post("/state-of-registry", $("#frmStateOfRegistry").serialize(), function(data){
            if(data == 'exist'){
                $("#loader").html(`<i class=\"mdi mdi-alert\"></i> ${$state_of_registry} already exists.`);
                return;
            }
            $url= '/state-of-registry';
            window.location = $url;
        });
    });

    $("#updateStateOfRegistry").click(function(e){
        e.preventDefault();
        $state_of_registry = $("#state_of_registry").val();
        if($state_of_registry == ""){
            $("#loader").html('State of Registry is required.').addClass('error');
            return
        }
        $id = $("#id").val();
        $("#loader").html('<img src="/images/ajax.gif">Please wait...').addClass('error');
        $.post("/state-of-registry/"+$id, $("#frmStateOfRegistry").serialize(), function(data){
            if(data == 'exist'){
                $("#loader").html(`<i class=\"mdi mdi-alert\"></i> ${$state_of_registry} already exists.`);
                return;
            }
            $url= '/state-of-registry';
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
    $(".deleteStateOfRegsitry").click(function(){
        $id = $(this).attr("value");
        $name = $(this).attr("title");
        $ask = confirm("Are you sure you want to delete "+$name.toLowerCase()+"?");
        if($ask){
            $("#deleteLoader")
            .html(
                '<img src=\'/images/ajax.gif\'>please wait...'
            )
            .addClass('error');
            $.post("/state-of-registry/"+$id, $("#frmDeleteStateOfRegistry").serialize(), function(data){
               if(data=='cant_delete'){
                   $("#deleteLoader").html("<i class='mdi mdi-alert'></i> Sorry, State of Registry is in use by an FOCC. Won't Delete.").css({color:'green'});
                   return;
               }
               $url = '/state-of-registry';
               window.location = $url;
            })
        }
        return;
    });

})