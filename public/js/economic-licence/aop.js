$(function($){
    
    $(".operatorChecker").click(function(){
        $value = $(this).attr("value");
        if($value == 1 ){
            $("#existingAocHolder").css({display:'block'});
            $("#generalAviationHolder").css({display:'none'});
            $("#aocHolderInUse").attr("name", "operator");
            $("#aocHolderGa").removeAttr("name");
        } else {
            $("#generalAviationHolder").css({display:'block'});
            $("#existingAocHolder").css({display:'none'});
            $("#aocHolderGa").attr("name", "operator");
            $("#aocHolderInUse").removeAttr("name");
        }
        $("#operatorTypeChecker").val($value);
    })

    $(document).on("change", "#aircraft_maker_id", function(){
        $.post("/aircrafttypebyaircraftmaker", $("#frmfoccandfocc").serialize(), function(data){
            $("#aircraftTypeDropper").html(data);
            $("#registrationMarks").val("");
        });
    });


    $("#addAop").click(function(event){
        event.preventDefault();
        if(validatAopRequest()==false) return;
        $("#frmAop").submit();
    });

    $("#updateAop").click(function(event){  
        event.preventDefault();
        if(validatAopRequest()==false) return;
        $("#frmAop").submit();
    });

    $("#changePhoto").click(function() {
        $(this).css({display:'none'});
        $("#file").removeAttr('disabled', 'disabled');
        $("#photoChecker").val(1);
    })

    function validatAopRequest() {
        $operatorTypeChecker = $("#operatorTypeChecker").val();
        if($operatorTypeChecker == ''){
            $("#loader").html("Type of operator is required").addClass('error');
            return false;
        }
        else{
            if($operatorTypeChecker == 1){
                $aocHolderInUse = $("#aocHolderInUse").val();
                if($aocHolderInUse == 0){
                    $("#loader").html("Operator is required").addClass('error');
                    return false;
                }
            }
            else{
                if($operatorTypeChecker == 2){
                    $aocHolderGa = $("#aocHolderGa").val();
                    if($aocHolderGa == 0){
                        alert($aocHolderGa)
                        $("#loader").html("Operator is required").addClass('error');
                        return false;
                    }
                }
            }
        }
        
        $licenceNumber = $("#licenceNumber").val();
        if($licenceNumber == ""){
            $("#loader").html("Licence Number  is required").addClass('error');
            return false;
        }

        $file = $("#file").val();
        $photoChecker = $("#photoChecker").val();
        if($file == "" && $photoChecker == 0) {
            $("#loader").html("Licence certificate upload is required").addClass('error');
            return false;
        }
        else{
            if($file == "" && $photoChecker == 1){
                $("#loader").html("Licence certificate upload is required").addClass('error');
                return false;
            }
            if($file !=""){
                var ftype = $("#ftype").val();
                validateFile(ftype);
                var filecheck = $("#filecheck").val();
                if(filecheck == "0"){return;}
            }
        }

        $dateOfFirstIssue = $("#dateOfFirstIssue").val();
        if($dateOfFirstIssue == ""){
            $("#loader").html("Date of first issue is required").addClass('error');
            return false;
        }

        $dateOfLastRenewal = $("#dateOfLastRenewal").val();
        if($dateOfLastRenewal == ""){
            $("#loader").html("Date of last renewal is required").addClass('error');
            return false;
        }

        $dateOfExpiry = $("#dateOfExpiry").val();
        if($dateOfExpiry == ""){
            $("#loader").html("Date of expiry is required").addClass('error');
            return false;
        }
    }



    $("#frmAop").ajaxForm(function(data) {
        if(data == 'exists') {
            $("#loader").html("This record already exists").addClass('error');
            return false;
        }
        else {
            if(data == 'saved' || data == "updated") {
                $("#loader").html("Record Succesfully "+data).addClass('error');
                window.location = '';
            }
        }
    })

    $(".amoStatusChecker").click(function(){
        $value = $(this).attr('value');
        $("#amo_holder_status").val($value);
    })

    $("#updateFocc").click(function(event){
        event.preventDefault();
        $operatorTypeChecker = $("#operatorTypeChecker").val();
        if($operatorTypeChecker == ''){
            $("#loader").html("Type of operator is required").addClass('error');
            return false;
        }
        else{
            if($operatorTypeChecker == 1){
                $aocHolderInUse = $("#aocHolderInUse").val();
                if($aocHolderInUse == 0){
                    $("#loader").html("Operator is required").addClass('error');
                    return false;
                }
            }
            else{
                if($operatorTypeChecker == 2){
                    $aocHolderGa = $("#aocHolderGa").val();
                    if($aocHolderGa == 0){
                        alert($aocHolderGa)
                        $("#loader").html("Operator is required").addClass('error');
                        return false;
                    }
                }
            }
        }
        $focc_no = $("#focc_no").val();
            if($focc_no == ""){
                $("#loader").html("FOCC NO. is required").addClass('error');
                return false;
            }
        $mcc_no = $("#mcc_no").val();
            if($mcc_no == ""){
                $("#loader").html("MCC NO. is required").addClass('error');
                return false;
            }
        $state_of_registry_id = $("#state_of_registry_id").val();
            if($state_of_registry_id == 0){
                $("#loader").html("State of registry is required").addClass('error');
                return false;
            }
        $registered_owner = $("#registered_owner").val();
            if($registered_owner == ""){
                $("#loader").html("Registered owner is required").addClass('error');
                return false;
            }
        $aircraft_maker_id = $("#aircraft_maker_id").val();
            if($aircraft_maker_id == 0){
                $("#loader").html("Aircraft maker is required").addClass('error');
                return false;
            }
        $aircraft_type_id = $("#aircraft_type_id").val();
            if($aircraft_type_id == 0){
                $("#loader").html("Aircraft type is required").addClass('error');
                return false;
            }
        $aircraft_reg_no = $("#aircraft_reg_no").val();
            if($aircraft_reg_no == 0){
                $("#loader").html("Aircraft registration number is required").addClass('error');
                return false;
            }
        $dateOfFirstIssue = $("#dateOfFirstIssue").val();
            if($dateOfFirstIssue == ""){
                $("#loader").html("Date of first issue is required").addClass('error');
                return false;
            }
        $id = $("#id").val();
        $.post('/focc-and-mcc/'+$id, $('#frmfocc').serialize(), function(data){
            $("#loader").html('<img src=\'/images/ajax.gif\'>Please Wait...').addClass('error');
                if(data =="exists"){
                    $("#loader").html("This record already exists.").addClass("error");
                    return false;
                }
                else{
                    if(data == 'updated'){
                        window.location = '/focc-and-mcc';
                    }
                }
        });
    });

    $("#downloadFocc").click(function(){
        var name = Math.random().toString().substring(7);
        $("#exportTableData").table2excel({
            filename:`focc-${name}.xls`
        });
    });

    //Delete an AOP
    $(".deleteFocc").click(function(){
        $id = $(this).attr("value");
        $name = $(this).attr("title");
        $ask = confirm("Are you sure you want to "+$name.toLowerCase()+"?");
        if($ask){
            $("#deleteLoader")
            .html(
                '<img src=\'/images/ajax.gif\'>please wait...'
            )
            .addClass('error')
            .css({float:'right'});
            $.post("/focc-and-mcc/"+$id, $("#deleteFocc").serialize(), function(data){
               if(data=='deleted'){
                   $url = '/focc-and-mcc';
                   window.location = $url;
               }
            })
        }
        return;
    });


    // change function for aop operators sort
    $("#operator").change(function() {
        $operator = $("#operator").val();
        $("#contentDropper").html('<img src=\'/images/ajax.gif\'>please wait...');
        $.get('/aop-operator-sort', {direction:$operator}, function(data) {
            $('#contentDropper').html(data);
        })
        
    })

    
    // on change for filtering status
    $("#chooseStatus").on("change", function(){
        $status = $("#chooseStatus").val();
        if($status == '0'){
            return false;
        }
        $("#contentDropper").html('<img src=\'/images/ajax.gif\'>please wait...').addClass('error');

        if($status == 'active'){
            $.get('/aop-active', {active:$status}, function(data){
                $("#contentDropper").html(data).removeClass('error');
            });    
        }

        if($status == 'expired'){
            $.get('/aop-expired', {expired:$status}, function(data){
                $("#contentDropper").html(data).removeClass('error');
            });    
        }

        if($status == 'expiringSoon'){
            $.get('/aop-expiring-soon', {expiringSoon:$status}, function(data){
                $("#contentDropper").html(data).removeClass('error');
            });    
        }
    });

    $("#downloadAOP").click(function(){
        var name = Math.random().toString().substring(7);
        $("#exportTableData").table2excel({
            filename:`airline-operating-permit-${name}.xls`
        });
    });

});