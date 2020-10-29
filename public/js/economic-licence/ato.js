$(function($){
    $("#addAto").click(function(event){
        event.preventDefault();
        if(validatAtoRequest()==false) return;
        $("#frmAto").submit();
    });

    $("#updateAto").click(function(event){  
        event.preventDefault();
        if(validatAtoRequest()==false) return;
        $("#frmAto").submit();
    });

    $("#changePhoto").click(function() {
        $(this).css({display:'none'});
        $("#file").removeAttr('disabled', 'disabled');
        $("#photoChecker").val(1);
    })

    function validatAtoRequest() {
        $trainingOrganizationId = $("#trainingOrganizationId").val();
        if($trainingOrganizationId == '0'){
            $("#loader").html("Training organization is required").addClass('error');
            return false;
        }

        $approvalNumber = $("#approval_no").val();
        if($approvalNumber == ""){
            $("#loader").html("Approval Number  is required").addClass('error');
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



    $("#frmAto").ajaxForm(function(data) {
        if(data == 'exists') {
            $("#loader").html("This record already exists").addClass('error');
            return false;
        }
        else {
            if(data == 'saved' || data == "updated") {
                $("#loader").html("Record Succesfully "+data).addClass('error');
                window.location = '/economic-licence/ato';
            }
        }
    })

   
    

    $("#downloadFocc").click(function(){
        var name = Math.random().toString().substring(7);
        $("#exportTableData").table2excel({
            filename:`focc-${name}.xls`
        });
    });

    //Delete an ATO
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

    
    // change function for paas operators sort
    $("#operator").change(function() {
        $operator = $("#operator").val();
        $("#contentDropper").html('<img src=\'/images/ajax.gif\'>please wait...');
        $.get('/ato-operator-sort', {direction:$operator}, function(data) {
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
            $.get('/ato-active', {active:$status}, function(data){
                $("#contentDropper").html(data).removeClass('error');
            });    
        }

        if($status == 'expired'){
            $.get('/ato-expired', {expired:$status}, function(data){
                $("#contentDropper").html(data).removeClass('error');
            });    
        }

        if($status == 'expiringSoon'){
            $.get('/ato-expiring-soon', {expiringSoon:$status}, function(data){
                $("#contentDropper").html(data).removeClass('error');
            });    
        }
    });

    $("#downloadato").click(function(){
        var name = Math.random().toString().substring(7);
        $("#exportTableData").table2excel({
            filename:`approved-training-organization-${name}.xls`
        });
    });

});