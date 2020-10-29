$(document).ready(function(){
    $("#addAircraft").click(function(event){
        event.preventDefault();
        aircraftValidation();
    });

    $("#aoc_holder_id").change(function(){
        $aoc_holder_id = $("#aoc_holder_id").val();
        $.post('/get-aircraft-make/'+$aoc_holder_id, $("#frmAircraft").serialize(), function(data){
            $("#aircraftconntent").html(data); 
        });
    });

    $(document).on("change", "#aircraft_maker_id", function(){
        $aircraft_maker_id = $("#aircraft_maker_id").val();
        $.post('/get-aircraft-type/'+$aircraft_maker_id, $("#frmAircraft").serialize(), function(data){
            $("#aircraftTypeContent").html(data); 
        });
    });
    
    const aircraftValidation = () => {
        $aoc_holder_id = $("#aoc_holder_id").val();
            if($aoc_holder_id == 0){
                $('#loader').html('<i class="mdi mdi-alert"></i>Registered operator is required').addClass('error');
                return false;
            }
        $aircraft_maker_id = $('#aircraft_maker_id').val();
        if($aircraft_maker_id == 0){
            $('#loader').html('<i class="mdi mdi-alert"></i>Aircraft maker is required').addClass('error');
            return false;
        }
        $registration_mark = $('#registration_mark').val();
            if($registration_mark == ''){
                $('#loader').html('<i class="mdi mdi-alert"></i>Registration mark is required').addClass('error');
                return false;
            }
        $aircraft_type = $('#aircraft_type').val();
        if($aircraft_type == '0'){
            $('#loader').html('<i class="mdi mdi-alert"></i>Aircraft type is required').addClass('error');
            return false;
        }
        $aircraft_serial_number = $('#aircraft_serial_number').val();
        if($aircraft_serial_number == ''){
            $('#loader').html('<i class="mdi mdi-alert"></i>Aircraft serial number is required').addClass('error');
            return false;
        }
        $year_of_manufacture = $('#year_of_manufacture').val();
        if($year_of_manufacture == ''){
            $('#loader').html('<i class="mdi mdi-alert"></i>Year of manufacture is required').addClass('error');
            return false;
        }
        $registration_date = $('#registration_date').val();
        if($registration_date == ''){
            $('#loader').html('<i class="mdi mdi-alert"></i>Registration date is required').addClass('error');
            return false;
        }
        $registered_owner = $('#registered_owner').val();
            if($registered_owner == ''){
                $('#loader').html('<i class="mdi mdi-alert"></i>Registration owner is required').addClass('error');
                return false;
            }
        $c_of_a_status = $('#c_of_a_status').val();
            if($c_of_a_status != ''){
                $file = $('#file').val();
                    if($file == ''){
                        $('#loader').html('<i class="mdi mdi-alert"></i>Upload certificate of Airworthiness').addClass('error');
                        return false; 
                    }
                    else{
                        if($file !=''){
                            var ftype = $("#ftype").val();
                            validateFile(ftype);
                            var filecheck = $("#filecheck").val();
                            if(filecheck == "0"){return;}
                        }
                    }
            }
        $weight = $('#weight').val();
            if($weight == ''){
                $('#loader').html('<i class="mdi mdi-alert"></i>Aircraft weight is required').addClass('error');
                return false;
            }
        $("#frmAircraft").submit();
        $("#loader").html('<img src=\'/images/ajax.gif\'>Please wait...').addClass('error');
    }

    $("#frmAircraft").ajaxForm(function(data){
        if (data === 'exists'){
            $('#loader').html('<i class=\'mdi mdi-alert\'></i>Sorry, this record already exists.').addClass('error');
            return false;
        }
        else{
            if(data == 'saved'){
                window.location = '';
            }
            else{
                if(data == 'updated'){
                    window.location = '/add-new-aircraft';
                }
            }
        }
    });

    $("#unlock").click(function(){
        $ask = confirm('Are you sure you want to upload a new certificate of Airworthiness?');
        if($ask){
            $("#file").removeAttr('disabled');
            $("#checker").val(1);
            $("#unlock").css({display:'none'});
        }
        else{
            return false;
        }
    });

    $("#updateAircraft").click(function(){
        event.preventDefault();
        updateAircraftValidation();
    });

    const updateAircraftValidation = () => {
        $aoc_holder_id = $("#aoc_holder_id").val();
            if($aoc_holder_id == 0){
                $('#loader').html('<i class="mdi mdi-alert"></i>Registered operator is required').addClass('error');
                return false;
            }
        $aircraft_maker_id = $('#aircraft_maker_id').val();
            if($aircraft_maker_id == 0){
                $('#loader').html('<i class="mdi mdi-alert"></i>Aircraft maker is required').addClass('error');
                return false;
            }
        $registration_mark = $('#registration_mark').val();
            if($registration_mark == ''){
                $('#loader').html('<i class="mdi mdi-alert"></i>Registration mark is required').addClass('error');
                return false;
            }
        $aircraft_type = $('#aircraft_type').val();
            if($aircraft_type == '0'){
                $('#loader').html('<i class="mdi mdi-alert"></i>Aircraft type is required').addClass('error');
                return false;
            }
        $aircraft_serial_number = $('#aircraft_serial_number').val();
            if($aircraft_serial_number == ''){
                $('#loader').html('<i class="mdi mdi-alert"></i>Aircraft serial number is required').addClass('error');
                return false;
            }
        $year_of_manufacture = $('#year_of_manufacture').val();
            if($year_of_manufacture == ''){
                $('#loader').html('<i class="mdi mdi-alert"></i>Year of manufacture is required').addClass('error');
                return false;
            }
        $registration_date = $('#registration_date').val();
            if($registration_date == ''){
                $('#loader').html('<i class="mdi mdi-alert"></i>Registration date is required').addClass('error');
                return false;
            }
        $registered_owner = $('#registered_owner').val();
            if($registered_owner == ''){
                $('#loader').html('<i class="mdi mdi-alert"></i>Registration owner is required').addClass('error');
                return false;
            }
        $checker = $('#checker').val();
            if($checker == 1){
                $file = $('#file').val();
                    if($file == ''){
                        $('#loader').html('<i class="mdi mdi-alert"></i>Upload certificate of Airworthiness').addClass('error');
                        return false; 
                    }
                    else{
                        if($file !=''){
                            var ftype = $("#ftype").val();
                            validateFile(ftype);
                            var filecheck = $("#filecheck").val();
                            if(filecheck == "0"){return;}
                        }
                    }
            }
        $weight = $('#weight').val();
            if($weight == ''){
                $('#loader').html('<i class="mdi mdi-alert"></i>Aircraft weight is required').addClass('error');
                return false;
            }
        $("#frmAircraft").submit();
        $("#loader").html('<img src=\'/images/ajax.gif\'>Please wait...').addClass('error');
    }


    $("#aocholderid").change(function(){
        $aocholderid = $("#aocholderid").val();
            if($aocholderid=="0"){
                return false;
            }
        $("#contentDropper").html('<img src=\'/images/ajax.gif\'> Please wait...').addClass('error');
        $.post('/getallaircraftsbyaoc', $("#frmgetAircraftByAoc").serialize(), function(data){
            $array_content = data.split('`');
            $("#aircraftMakerDropper").html($array_content[0]);
            $("#contentDropper").html($array_content[1]).removeClass('error'); 
        });
    });

    $(document).on("change", "#aircraftMakerId", function(){
        $aircraft_maker_id = $("#aircraftMakerId").val();
        $aocholderid = $("#aocholderid").val();
        $("#contentDropper").html('<img src=\'/images/ajax.gif\'> Please wait...').addClass('error');
        $.post('/getallaircraftsbyaocandmake', $("#frmgetAircraftByAoc").serialize(), function(data){
            $("#contentDropper").html(data).removeClass('error'); 
        });

    })

    $("#downloadAircraftStatus").click(function(){
        var name = Math.random().toString().substring(7);
        $("#exportTableData").table2excel({
            filename:`Aircracraft-Status-${name}.xls`
        });
    });

    //Delete an ac/status
    $(".deleteAircraftStatus").click(function(){
        $id = $(this).attr("value");
        $name = $(this).attr("title");
        $ask = confirm("Are you sure you want to "+$name+"?");
        if($ask){
            $("#deleteLoader")
            .html(
                '<img src=\'/images/ajax.gif\'>please wait...'
            )
            .addClass('error');
            $.post("/add-new-aircraft/"+$id, $("#deleteAircraftStatus").serialize(), function(data){
               if(data=='cant_delete'){
                   $("#deleteLoader").html('<i class="mdi mdi-alert"></i>This aircraft status record is in use in FOCC. Won\'t delete.');
                   return;
               }
               $url = '/add-new-aircraft';
               window.location = $url;
            })
        }
        return;
    });


    $("#remarks").change(function(){
        $remarks = $("#remarks").val();
        if($remarks == 'active'){
            $.post('/aircraftstatusbyactive/', $("#frmgetAircraftByAoc").serialize(), function(data){
                $("#contentDropper").html(data); 
            });
        }
        if($remarks == 'expiringSoon') {
            $.post('/aircraftstatusexpiringsoon/', $("#frmgetAircraftByAoc").serialize(), function(data){
                $("#contentDropper").html(data); 
            });
        }
        if($remarks == 'expired') {
            $.post('/aircraftstatusbyexpired/', $("#frmgetAircraftByAoc").serialize(), function(data){
                $("#contentDropper").html(data); 
            });
        }
    });

    $('#aircraftType').change(function() {
        $value = $(this).val();
        $("#contentDropper").html('<img src=\'/images/ajax.gif\'> Please wait...').addClass('error');
        $.post('/get-aircraft-type-ac-status', $("#frmgetAircraftByAoc").serialize(), function(data) {
            $("#contentDropper").html(data).removeClass('error'); 
        })
    })

    $("#registration_marks").change(function(){
        $registration_marks = $("#registration_marks").val();
        $.post('/aircraftstatusbyregmarks/', $("#frmgetAircraftByAoc").serialize(), function(data){
            $("#contentDropper").html(data); 
        });
    });


});

