$(function($){
    $("#addForeignAmo").click(function(event){
        event.preventDefault();
        tinyMCE.triggerSave();

        $amo_holder  = $("#amo_holder").val();
        if($amo_holder == ""){
            $("#loader").html('<i class=\'mdi mdi-alert\'></i>AMO holder is required').addClass('error');
            return false;
        }
        $country_id  = $("#regional_country_id").val();
            if($country_id == 0){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>Country is required').addClass('error');
                return false;
            }
        $ratings_capabilities = $('#ratings_capabilities').val();
            if($ratings_capabilities == ""){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>Ratings / Capabilities is required').addClass('error');
                return false;
            }
        $amo_number = $('#amo_number').val();
            if($amo_number == ""){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>AMO number is required').addClass('error');
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
        $expiry = $('#expiry').val();
            if($expiry == ""){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>Expiry date is required').addClass('error');
                return false;
            }
        $("#frmForeignAmo").submit();
        $("#loader").html('<img src=\'/images/ajax.gif\'> Please wait...').addClass('error');
    });

    $("#unlockAmo").click(function(){
        $ask = confirm("Are you sure you want to upload a new foreign AMO ?");
        if($ask){
            $("#amoFileChecker").val(1);
            $("#file").removeAttr("disabled");
            $(this).css({display:'none'});
        }
        else{
            return false;
        }
    });


    $("#updateForeignAmo").click(function(event){
        event.preventDefault();
        tinyMCE.triggerSave();

        $amo_holder  = $("#amo_holder").val();
            if($amo_holder == ""){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>AMO holder is required').addClass('error');
                return false;
            }
        $country_id  = $("#regional_country_id").val();
            if($country_id == 0){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>Country is required').addClass('error');
                return false;
            }
        $ratings_capabilities = $('#ratings_capabilities').val();
            if($ratings_capabilities == ""){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>Ratings / Capabilities is required').addClass('error');
                return false;
            }
        $amo_number = $('#amo_number').val();
            if($amo_number == ""){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>AMO number is required').addClass('error');
                return false;
            }
        $amoFileChecker = $("#amoFileChecker").val();
        $file = $('#file').val();
            if($amoFileChecker == 1 && $file == ""){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>Upload AMO approval').addClass('error');
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
        $expiry = $('#expiry').val();
            if($expiry == ""){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>Expiry date is required').addClass('error');
                return false;
            }
        $("#frmForeignAmo").submit();
        $("#loader").html('<img src=\'/images/ajax.gif\'> Please wait...').addClass('error');
    });



    $("#frmForeignAmo").ajaxForm(function(data){
        if(data == "exists"){
            $("#loader").html('<i class=\'mdi mdi-alert\'></i>An AMO with the record already exists.').addClass('error');
            return false;        
        }
        else{
            $array = data.split('`');
            $answerStatus = $array[0];
            $insertedIdentity = $array[1];
            if($answerStatus === "saved" || "updated"){
                $url = `/assignaircrafttype-to-maker/${$insertedIdentity}`;
                window.location=$url;
            }
        }
    });

    // on change for filtering status
    $("#chooseStatus").on("change", function(){
        $status = $("#chooseStatus").val();
        if($status == '0'){
            return false;
        }
        $("#contentDropper").html('<img src=\'/images/ajax.gif\'>please wait...').addClass('error');

        if($status == 'active'){
            $.post('/getforeignamobyactivestatus', $("#frmForeignAmobyStatus").serialize(), function(data){
                $("#contentDropper").html(data).removeClass('error');
            });    
        }

        if($status == 'expire'){
            $.post('/getforeignamobyexpiredstatus', $("#frmForeignAmobyStatus").serialize(), function(data){
                $("#contentDropper").html(data).removeClass('error');
            });    
        }

        if($status == 'expiring soon'){
            $.post('/getforeignamobyexpiringstatus', $("#frmForeignAmobyStatus").serialize(), function(data){
                $("#contentDropper").html(data).removeClass('error');
            });    
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
        $("#selectorCriteria").val('foreign_amo_holder');
        $('#direction').val($amoHolder);
        $("#contentDropper").html('<img src=\'/images/ajax.gif\'>please wait...').addClass('error');
        $.post('/foreign-amo-sorts', $("#frmForeignAmobyStatus").serialize(), function(data){
            $("#contentDropper").html(data).removeClass('error');
        })
    });

    // on change for country
    $("#country_id").change(function(){
        $country_id = $("#country_id").val();
        if($country_id == 0) {
            $("#selectorCriteria").val('');
            $("#direction").val('');
            return
        }
        $("#selectorCriteria").val('country');
        $('#direction').val($country_id);
        $("#contentDropper").html('<img src=\'/images/ajax.gif\'>please wait...').addClass('error');
        $.post('/foreign-amo-sorts', $("#frmForeignAmobyStatus").serialize(), function(data){
            $("#contentDropper").html(data).removeClass('error');
        })
    });
    

    $("#downloadForeignAmo").click(function(){
        var name = Math.random().toString().substring(7);
        $("#exportTableData").table2excel({
            filename:`amo-foreign-${name}.xls`
        });
    });

    //Delete an Foreign Amo
    $(".deleteForeignAmo").click(function(){
        $id = $(this).attr("value");
        $name = $(this).attr("title");
        $ask = confirm("Are you sure you want to "+$name.toLowerCase()+"?");
        if($ask){
            $("#deleteLoader")
            .html(
                '<img src=\'/images/ajax.gif\'>please wait...'
            )
            .addClass('error');
            $.post("/amo-foreign/"+$id, $("#deleteForeignAmo").serialize(), function(data){
            if(data == 'deleted')
            {
                $url = '/amo-foreign';
                window.location = $url;
                return;
            }
            $("#deleteLoader").html('<i class="mdi mdi-alert"></i>Oops! Something wen\'t wrong. Internal Server Error.');
            return;  
            })
        }
        return;
    });


















});