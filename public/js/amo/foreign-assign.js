$(document).ready(function(){
   
    $(document).on("change", "#aircraft_maker_id", function(){
     $('#contentDropper').html('<img src=\'/images/ajax.gif\'>Please wait...').addClass('error');
     $.post('/getAircraftTypes', $("#frmAssignRatingstoHolder").serialize(), function(data){
         $('#contentDropper').html(data).removeClass('error');
     });
    });

    $("#addForeignAmoRatings").click(function(){
        $aircraft_maker_id = $("#aircraft_maker_id").val();
            if($aircraft_maker_id == ""){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>An Aircraft Maker is required').addClass('error');
                return false;
            }
            $.post('/assignaircrafttypetoamoholder', $('#frmAssignRatingstoHolder').serialize(), function(data){
                $("#loader").html('<img src=\'/images/ajax.gif\'>Please wait...').addClass('error');
                if(data=='saved'){
                    window.location=''
                }
            });
    });

    $('.aircraftType').click(function(){
        $checked = $(this).is(':checked');
        if($checked){
            $('#checkboxValidator').val(1);
        }
        else{
            $('#checkboxValidator').val(0);
        }
    })

    $('#removeRatings').click(function(e){
        e.preventDefault();
        $checkboxValidator = $("#checkboxValidator").val();
        if($checkboxValidator == 0) {
            alert('You have to select at least one aircraft before removing an aircraft.');
            return false;
        }
        $foreign_amo_holder = $('#foreign_amo_id').val();
        $.post('/delete-foreign-ratings', $("#frmDeleteRatings").serialize(), function(data){
            $("#errorLoader").html('<img src=\'/images/ajax.gif\'>Please wait...').addClass('error');
            if(data == 'deleted'){
                window.location = '';
            }
        });
    })

});