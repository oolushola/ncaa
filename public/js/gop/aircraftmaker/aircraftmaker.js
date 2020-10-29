$(function(){
    
    $('#addNewAircraftMaker').click((event)=>{
        event.preventDefault();
        validateAircraftMaker();
    });

    $('#updateAircraftMaker').click((event)=>{
        event.preventDefault();
        validateUpdateAircraftMaker();
    });


    const validateAircraftMaker = () => {
        $aircraft_maker = $("#aircraft_maker").val();
            if(!$aircraft_maker){
                $("#errContainer").html(' - Enter the maker of the aircraft').addClass('error');
                $("#aircraft_make").focus();
                return false;
            }
        $.post('/aircraft-make', $("#frmAircraftMake").serialize(), function(data){
            if(data === 'exists'){
                $("#errContainer").addClass('error').html('Sorry, this aircraft make already exists.');
                return false;
            }
            else{
                if(data === 'save'){
                    $("#loader").html('<img src=\'/images/ajax.gif\'>Please Wait...').addClass('error');
                    $url = '';
                    window.location = $url;
                }
            }
        })
    }

    const validateUpdateAircraftMaker = () => {
        $aircraft_maker = $("#aircraft_maker").val();
            if(!$aircraft_maker){
                $("#errContainer").html(' - Enter the maker of the aircraft').addClass('error');
                $("#aircraft_make").focus();
                return false;
            }
        $id = $("#id").val();
        $.post('/aircraft-make/'+$id, $("#frmUpdateAircraftMake").serialize(), function(data){
            if(data === 'exists'){
                $("#errContainer").addClass('error').html('Sorry, this aircraft make already exists.');
                return false;
            }
            else{
                if(data === 'updated'){
                    $("#loader").html('<img src=\'/images/ajax.gif\'>Please Wait...').addClass('error');
                    $url = '/aircraft-make';
                    window.location = $url;
                }
            }
        })
    }

    // Delete Aircraft maker
    $(".deleteAircraftMake").click(function(){
        $id = $(this).attr("value");
        $name = $(this).attr("title");
        $ask = confirm("Are you sure you want to "+$name.toLowerCase()+"?");
        if($ask){
            $("#deleteLoader")
            .html(
                '<img src=\'/images/ajax.gif\'>please wait...'
            )
            .addClass('error');
            $.post("/aircraft-make/"+$id, $("#deleteAircraftMaker").serialize(), function(data){
               if(data=='cant_delete'){
                   $("#deleteLoader").html("<i class='mdi mdi-alert'></i> Sorry, this aircraft make is in use by by either an AOC, Aircraft Status or Aircraft Type. Won't Delete.").css({color:'green'});
                   return;
               }
               $url = '/aircraft-make';
               window.location = $url;
            })
        }
        return;
    });
})