$(function($){
    
    $(".operatorChecker").click(function(){
        $value = $(this).attr("value");
        if($value == 1 ){
            $("#existingAocHolder").css({display:'block'});
            $("#generalAviationHolder").css({display:'none'});
            $("#travelAgencyHolder").css({display:'none'});
            $('#otherHolder').css({ display: 'none'})

            $("#aocHolderInUse").attr("name", "operator_type");
            $("#aocHolderGa").removeAttr("name");

        } else if($value == 2) {
            $("#generalAviationHolder").css({display:'block'});
            $("#existingAocHolder").css({display:'none'});
            $("#travelAgencyHolder").css({display:'none'});
            $('#otherHolder').css({ display: 'none'})

            $("#aocHolderGa").attr("name", "operator_type");
            $("#aocHolderInUse").removeAttr("name");
        }
        else if($value == 3) {
            $("#travelAgencyHolder").css({display:'block'});
            $("#existingAocHolder").css({display:'none'});
            $("#generalAviationHolder").css({display:'none'});
            $('#otherHolder').css({ display: 'none'})

            $("#aocHolderGa").attr("name", "operator_type");
            $("#aocHolderInUse").removeAttr("name");
        }
        else {
            $("#travelAgencyHolder").css({display:'none'});
            $("#existingAocHolder").css({display:'none'});
            $("#generalAviationHolder").css({display:'none'});
            $('#otherHolder').css({ display: 'block'})

            $("#aocOthers").attr("name", "operator_type");
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


    $("#addAtol").click(function(event){
        event.preventDefault();
        if(validateAtolRequest()==false) return;
        $("#frmAtol").submit();
    });

    $("#updateAtol").click(function(event){  
        event.preventDefault();
        if(validateAtolRequest()==false) return;
        $("#frmAtol").submit();
    });

    $("#changePhoto").click(function() {
        $(this).css({display:'none'});
        $("#file").removeAttr('disabled', 'disabled');
        $("#photoChecker").val(1);
    })

    function validateAtolRequest() {
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
        
        $licenceNumber = $("#licence_no").val();
        if($licenceNumber == ""){
            $("#loader").html("Licence Number  is required").addClass('error');
            return false;
        }

        // $file = $("#file").val();
        // $photoChecker = $("#photoChecker").val();
        // if($file == "") {
        //     $("#loader").html("Licence certificate upload is required").addClass('error');
        //     return false;
        // }
        // else{
        //     if($file == "" && $photoChecker == 1){
        //         $("#loader").html("Licence certificate upload is required").addClass('error');
        //         return false;
        //     }
        //     if($file !=""){
        //         var ftype = $("#ftype").val();
        //         validateFile(ftype);
        //         var filecheck = $("#filecheck").val();
        //         if(filecheck == "0"){return;}
        //     }
        // }

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


    $("#frmAtol").ajaxForm(function(data) {
        if(data == 'exists') {
            $("#loader").html("This record already exists").addClass('error');
            return false;
        }
        else {
            if(data == 'saved' || data == "updated") {
                $("#loader").html("Record Succesfully "+data).addClass('error');
                window.location = '/economic-licence/atol';
            }
        }
    })

    $("#trainingOrganization").change(function() {
        $("#contentDropper").html('<img src=\'/images/ajax.gif\'>please wait...').addClass('error');
        $.post('/filter-atol-training-organization', $('#frmAtol').serializeArray(), function(data) {
            $("#contentDropper").html(data).removeClass('error');
        })
    });

    // on change for filtering status
    $("#chooseStatus").on("change", function(){
        $status = $("#chooseStatus").val();
        if($status == '0'){
            return false;
        }
        $("#contentDropper").html('<img src=\'/images/ajax.gif\'>please wait...').addClass('error');

        if($status == 'active'){
            $.post('/atol-active', $("#frmAtol").serialize(), function(data){
                $("#contentDropper").html(data).removeClass('error');
            });    
        }

        if($status == 'expiringSoon'){
            $.post('/atol-expiring-soon', $("#frmAtol").serialize(), function(data){
                $("#contentDropper").html(data).removeClass('error');
            });    
        }

        if($status == 'expired'){
            $.post('/atol-expired', $("#frmAtol").serialize(), function(data){
                $("#contentDropper").html(data).removeClass('error');
            });    
        } 
    });

    //Delete an ATOL
    $(".deleteAtol").click(function(){
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
            $.post("/economic-licence/atol/"+$id, $("#frmDeleteAtol").serialize(), function(data){
               if(data=='deleted'){
                   $url = '/economic-licence/atol';
                   window.location = $url;
               }
            })
        }
        return;
    });

    $("#downloadATOL").click(function(){
        var name = Math.random().toString().substring(7);
        $("#exportTableData").table2excel({
            filename:`air-travel-organization-licence-${name}.xls`
        });
    });

});