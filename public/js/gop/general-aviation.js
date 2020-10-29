$(function(){
    $("#addGeneralAviation").click(function(e){
        e.preventDefault();
        $general_aviation_name = $("#general_aviation_name").val();
        if($general_aviation_name == ""){
            $("#loader").html('General Aviation Name is required.').addClass('error');
            return
        }
        $("#loader").html('<img src="/images/ajax.gif">Please wait...').addClass('error');
        $.post("/general-aviation", $("#frmGeneralAviation").serialize(), function(data){
            if(data == 'exist'){
                $("#loader").html(`<i class=\"mdi mdi-alert\"></i> ${$general_aviation_name} already exists.`);
                return;
            }
            $url= '/general-aviation';
            window.location = $url;
        });
    });

    $("#updateGeneralAviation").click(function(e){
        e.preventDefault();
        $general_aviation_name = $("#general_aviation_name").val();
        if($general_aviation_name == ""){
            $("#loader").html('General Aviation Name is required.').addClass('error');
            return
        }
        $id = $("#id").val();
        $("#loader").html('<img src="/images/ajax.gif">Please wait...').addClass('error');
        $.post("/general-aviation/"+$id, $("#frmGeneralAviation").serialize(), function(data){
            if(data == 'exist'){
                $("#loader").html(`<i class=\"mdi mdi-alert\"></i> ${$general_aviation_name} already exists.`);
                return;
            }
            $url= '/general-aviation';
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
    $(".deleteGeneralAviation").click(function(){
        $id = $(this).attr("value");
        $name = $(this).attr("title");
        $ask = confirm("Are you sure you want to delete "+$name.toLowerCase()+"?");
        if($ask){
            $("#deleteLoader")
            .html(
                '<img src=\'/images/ajax.gif\'>please wait...'
            )
            .addClass('error');
            $.post("/general-aviation/"+$id, $("#frmDeleteGeneralAviation").serialize(), function(data){
               if(data=='cant_delete'){
                   $("#deleteLoader").html("<i class='mdi mdi-alert'></i> Sorry, general aviation is in use by an FOCC. Won't Delete.").css({color:'green'});
                   return;
               }
               $url = '/general-aviation';
               window.location = $url;
            })
        }
        return;
    });

})