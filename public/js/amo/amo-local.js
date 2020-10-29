$(function($){

    $(".amoHolder").click(function(){
       $value = $(this).attr("value");
       if($value==1){
           $("#existingAOCHolderBox").css({display:'block'});
           $("#newAOCHolderBox").css({display:'none'});
           $("#amoHolderChecker").val(1);
           $("#aoc_holder_id").attr("name", "aoc_holder_id");
           $("#non_aoc_holder").attr("name", "");
       }
       else{
           if($value == 2){
                $("#newAOCHolderBox").css({display:'block'});
                $("#existingAOCHolderBox").css({display:'none'});
                $("#amoHolderChecker").val(2);
                $("#aoc_holder_id").attr("name", "");
                $("#non_aoc_holder").attr("name", "aoc_holder_id");
           }
       }
    })

    $("#addLocalAmo").click(function(event){
        event.preventDefault();
        $amoHolderChecker = $("#amoHolderChecker").val();
            if($amoHolderChecker == "")
            {
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>Select AMO Holder Criteria').addClass('error');
                return false;
            }
            else{
                if($amoHolderChecker == 1)
                {
                    $aoc_holder_id  = $("#aoc_holder_id").val();
                    if($aoc_holder_id == 0){
                        $("#loader").html('<i class=\'mdi mdi-alert\'></i>AMO holder is required').addClass('error');
                        return false;
                    }
                }

                if($amoHolderChecker == 2)
                {
                    $non_aoc_holder  = $("#non_aoc_holder").val();
                    if($non_aoc_holder == ""){
                        $("#loader").html('<i class=\'mdi mdi-alert\'></i>Name of AMO holder is required').addClass('error');
                        return false;
                    }
                }
            }

        
        $amo_approval_number = $('#amo_approval_number').val();
            if($amo_approval_number == ""){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>AMO approval number is required').addClass('error');
                return false;
            }
        $file = $('#file').val();
            if($file == ""){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>Upload AMO approval number').addClass('error');
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
        // $aircraft_maker_id = $("#aircraft_maker_id").val();
        // if($aircraft_maker_id == ''){
        //     $("#loader").html('<i class=\'mdi mdi-alert\'></i>Aircraft Maker is required').addClass('error');
        //     return false;
        // }
        // $ratings_capabilities = $('#ratings_capabilities').val();
        //     if($ratings_capabilities == ""){
        //         $("#loader").html('<i class=\'mdi mdi-alert\'></i>Ratings / Capabilities is required').addClass('error');
        //         return false;
        //     }
        $maintenance_locations = $('#maintenance_locations').val();
            if($maintenance_locations == ""){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>Maintenance location is required').addClass('error');
                return false;
            }
        $expiry = $('#expiry').val();
            if($expiry == ""){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>Expiry date is required').addClass('error');
                return false;
            }
        $amo_pm_aprvl_pg_lep_file = $('#amo_pm_aprvl_pg_lep_file').val();
            if($amo_pm_aprvl_pg_lep_file == ""){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>AMO PM APRVL PG & LEP upload is required').addClass('error');
                return false;
            }
            else{
                if($amo_pm_aprvl_pg_lep_file !=''){
                    var ftype = $("#ftype").val();
                    validateAMOAprovl(ftype);
                    var filecheck = $("#filecheck").val();
                    if(filecheck == "0"){return;}
                }
            }
        $("#amo_pm_aprvl_pg_lep").removeAttr('disabled');    
        $("#frmLocalAmo").submit();
        $("#loader").html('<img src=\'/images/ajax.gif\'> Please wait...').addClass('error');
    });

    $("#unlockAmoAprovalNumber").click(function(){
        $ask = confirm("Are you sure you want to upload a new AMO approval number?");
        if($ask){
            $("#approvalNumberChecker").val(1);
            $("#file").removeAttr("disabled");
            $(this).css({display:'none'});
        }
        else{
            return false;
        }
    });

    $("#unlock_aprvl_amo").click(function(){
        $ask = confirm("Are you sure you want to upload a new AMO PM APRVL PG & LEP?");
        if($ask){
            $("#aprvlChecker").val(1);
            $("#amo_pm_aprvl_pg_lep_file").removeAttr("disabled");
            $(this).css({display:'none'});
        }
        else{
            return false;
        }
    });

    $("#updateLocalAmo").click(function(event){
        event.preventDefault();
        tinyMCE.triggerSave();

        $amoHolderChecker = $("#amoHolderChecker").val();
            if($amoHolderChecker == "")
            {
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>Select AMO Holder Criteria').addClass('error');
                return false;
            }
            else{
                if($amoHolderChecker == 1)
                {
                    $aoc_holder_id  = $("#aoc_holder_id").val();
                    if($aoc_holder_id == 0){
                        $("#loader").html('<i class=\'mdi mdi-alert\'></i>AMO holder is required').addClass('error');
                        return false;
                    }
                }

                if($amoHolderChecker == 2)
                {
                    $non_aoc_holder  = $("#non_aoc_holder").val();
                    if($non_aoc_holder == ""){
                        $("#loader").html('<i class=\'mdi mdi-alert\'></i>Name of AMO holder is required').addClass('error');
                        return false;
                    }
                }
            }

        $amo_approval_number = $('#amo_approval_number').val();
            if($amo_approval_number == ""){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>AMO approval number is required').addClass('error');
                return false;
            }
        $approvalNumberChecker = $('#approvalNumberChecker').val();
        $file = $("#file").val();
        if($approvalNumberChecker == 1 && $file == ''){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>Upload AMO approval number').addClass('error');
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
        // $aircraft_maker_id = $("#aircraft_maker_id").val();
        //     if($aircraft_maker_id == ''){
        //         $("#loader").html('<i class=\'mdi mdi-alert\'></i>Aircraft Maker is required').addClass('error');
        //         return false;
        //     }
        // $ratings_capabilities = $('#ratings_capabilities').val();
        //     if($ratings_capabilities == ""){
        //         $("#loader").html('<i class=\'mdi mdi-alert\'></i>Ratings / Capabilities is required').addClass('error');
        //         return false;
        //     }
        $maintenance_locations = $('#maintenance_locations').val();
            if($maintenance_locations == ""){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>Maintenance location is required').addClass('error');
                return false;
            }
        $expiry = $('#expiry').val();
            if($expiry == ""){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>Expiry date is required').addClass('error');
                return false;
            }
        $aprvlChecker = $('#aprvlChecker').val();
        $amo_pm_aprvl_pg_lep_file = $("#amo_pm_aprvl_pg_lep_file").val();
        if($aprvlChecker == 1 && $amo_pm_aprvl_pg_lep_file == ''){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>AMO PM APRVL PG & LEP upload is required').addClass('error');
                return false;
            }
            else{
                if($amo_pm_aprvl_pg_lep_file !=''){
                    var ftype = $("#ftype").val();
                    validateAMOAprovl(ftype);
                    var filecheck = $("#filecheck").val();
                    if(filecheck == "0"){return;}
                }
            }
            
        $("#amo_pm_aprvl_pg_lep").removeAttr('disabled');    
        $("#frmLocalAmo").submit();
        $("#loader").html('<img src=\'/images/ajax.gif\'> Please wait...').addClass('error');
    });


    $("#frmLocalAmo").ajaxForm(function(data){
        if(data == "exists"){
            $("#loader").html('<i class=\'mdi mdi-alert\'></i>An AMO with the record already exists.').addClass('error');
            return false;        
        }
        else{
            $array = data.split('`');
            $content = $array[0];
            $recid = $array[1];
            if($content === "saved" || "updated"){
                window.location="/local-ratings-capabilities/"+$recid;
            }
        }
    });

    // change of foreign amo holder
    $("#amoHolder").change(function(){
        $amoHolder = $("#amoHolder").val();
        if($amoHolder == 0) {
            $("#selectorCriteria").val('');
            $("#direction").val('');
            return
        }
        $("#selectorCriteria").val('aoc_holder_id');
        $('#direction').val($amoHolder);
        $("#contentDropper").html('<img src=\'/images/ajax.gif\'>please wait...').addClass('error');
        $.post('/local-amo-sorts', $("#frmgetlocalamobystatus").serialize(), function(data){
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
            $.post('/getlocalamobyactivestatus', $("#frmgetlocalamobystatus").serialize(), function(data){
                $("#contentDropper").html(data).removeClass('error');
            });    
        }

        if($status == 'expired'){
            $.post('/getlocalamobyexpiredstatus', $("#frmgetlocalamobystatus").serialize(), function(data){
                $("#contentDropper").html(data).removeClass('error');
            });    
        }

        if($status == 'expiring soon'){
            $.post('/getlocalamobyexpiringstatus', $("#frmgetlocalamobystatus").serialize(), function(data){
                $("#contentDropper").html(data).removeClass('error');
            });    
        }
    });

    $("#downloadLocalAMO").click(function(){
        var name = Math.random().toString().substring(7);
        $("#exportTableData").table2excel({
            filename:`local-amo-${name}.xls`
        });
    });

    //Delete an Local Amo
    $(".deleteLocalAmo").click(function(){
        $id = $(this).attr("value");
        $name = $(this).attr("title");
        $ask = confirm("Are you sure you want to "+$name+" local amo?");
        if($ask){
            $("#deleteLoader")
            .html(
                '<img src=\'/images/ajax.gif\'>please wait...'
            )
            .addClass('error');
            $.post("/amo-local/"+$id, $("#deleteLocalAmo").serialize(), function(data){
            if(data == 'deleted')
            {
                $url = '/amo-local';
                window.location = $url;
                return;
            }
            $("#deleteLoader").html('<i class="mdi mdi-alert"></i>Oops! Something wen\'t wrong. Internal Server Error.');
            return;  
            })
        }
        return;
    });


    $("#aircraft_maker_id").change(function(){
        $aircraft_maker_id = $("#aircraft_maker_id").val();
        $.post('/getaircrafttype/'+$aircraft_maker_id, $('#frmLocalAmo').serialize(), function(data){
            $("#aircraftTypeHolder").html(data);
        })
    })

});