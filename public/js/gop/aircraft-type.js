$(function(){
    $("#addNewAircraftType").click(function(e){
        e.preventDefault();
        $aircraft_maker_id = $("#aircraft_maker_id").val();
            if($aircraft_maker_id == ""){
                $("#loader").html('Aircraft make is required.').addClass('error');
                return
            }
        $aircraft_type = $("#aircraft_type").val();
        if($aircraft_type == ""){
            $("#loader").html('Aircraft type is required.').addClass('error');
            return
        }
        $("#loader").html('<img src="/images/ajax.gif">Please wait...').addClass('error');
        $.post("/aircraft-type", $("#frmAircraftType").serialize(), function(data){
            if(data == 'exist'){
                $("#loader").html("<i class=\"mdi mdi-alert\"></i> An aircraft type with this category already exists.");
                return;
            }
            $url= '/aircraft-type';
            window.location = $url;
        });
    });

    $("#updateAircraftType").click(function(e){
        e.preventDefault();
        $aircraft_maker_id = $("#aircraft_maker_id").val();
            if($aircraft_maker_id == ""){
                $("#loader").html('Aircraft make is required.').addClass('error');
                return
            }
        $aircraft_type = $("#aircraft_type").val();
        if($aircraft_type == ""){
            $("#loader").html('Aircraft type is required.').addClass('error');
            return
        }
        $id = $("#id").val();
        $("#loader").html('<img src="/images/ajax.gif">Please wait...').addClass('error');
        $.post("/aircraft-type/"+$id, $("#frmAircraftType").serialize(), function(data){
            if(data == 'exist'){
                $("#loader").html("<i class=\"mdi mdi-alert\"></i> An aircraft type with this category already exists.");
                return;
            }
            $url= '/aircraft-type';
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
    $(".deleteAircraftType").click(function(){
        $id = $(this).attr("value");
        $name = $(this).attr("title");
        $ask = confirm("Are you sure you want to delete "+$name.toLowerCase()+"?");
        if($ask){
            $("#deleteLoader")
            .html(
                '<img src=\'/images/ajax.gif\'>please wait...'
            )
            .addClass('error');
            $.post("/aircraft-type/"+$name+"-"+$id, $("#frmDeleteAircraftType").serialize(), function(data){
               if(data=='cant_delete'){
                   $("#deleteLoader").html("<i class='mdi mdi-alert'></i> Sorry, the Aircraft Type is in use at Aircraft Status. Won't Delete.").css({color:'green'});
                   return;
               }
               $url = '/aircraft-type';
               window.location = $url;
            })
        }
        return;
    });

})