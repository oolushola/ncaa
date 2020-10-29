$(function($){

    //get the button for submission here.
    $("#addNewAoc").click((event)=>{
        event.preventDefault();
        validateNewAOC();
    }); 

    $('.remarks').click(function(){
        $value = $(this).attr('value');
        $("#remarks").val($value);
    });

     const validateNewAOC = () =>{
        $('#errContainer').html('').removeClass('error');
        $aoc_holder = $("#aoc_holder").val();
            if(!$aoc_holder){
                $("#errContainer").html('- sorry, AOC Holder is required*').addClass('error');
                $("#aoc_holder").focus();
                return false;
            }
        $aoc_certificate_no = $("#aoc_certificate_no").val();
            if(!$aoc_certificate_no){
                $("#errContainer").html('- AOC Certificate Number is required*').addClass('error');
                $("#aoc_certificate_no").focus();
                return false;
            }
        $aoc_certificate = $("#aoc_certificate").val();
            if(!$aoc_certificate){
                $("#errContainer").html('- choose an AOC certificate file to upload').addClass('error');
                return false;
            }
            else{
				if(aoc_certificate !=""){
					var ftype = $("#ftype").val();
					validateaoccertfile(ftype);
					var filecheck = $("#filecheck").val();
					if(filecheck == "0"){return;}
				}
			}
        $issued_date = $("#issued_date").val();
            if(!$issued_date){
                $("#errContainer").html('- choose an issue date for the AOC holder').addClass('error');
                $("#issued_date").focus();
                return false;
            }
        $validity = $("#validity").val();
            if(!$validity){
                $("#errContainer").html('- choose the validity date for the AOC holder').addClass('error');
                $("#validity").focus();
                return false;
            }
        $ops_specs = $("#ops_specs").val();
            if(!$ops_specs){
                $("#errContainer").html('- choose an OPS Specs file to upload').addClass('error');
                return false;
            }
            else{
				if($ops_specs !=""){
					var ftype = $("#ftype").val();
					validateOpsSpecs(ftype);
					var filecheck = $("#filecheck").val();
					if(filecheck == "0"){return;}
				}
            }
            
        $part_g = $("#part_g").val();
            if(!$part_g){
                $("#errContainer").html('- choose a part G file to upload').addClass('error');
                return false;
            }
            else{
				if($part_g !=""){
					var ftype = $("#ftype").val();
					validatePartG(ftype);
					var filecheck = $("#filecheck").val();
					if(filecheck == "0"){return;}
				}
            }
        $remarks = $("#remarks").val();
            if(!remarks){
                $("#errContainer").html('- Choose the remark of the AOC Holder').addClass('error');
                return false;  
            }
        $("#frmNewAoc").submit();    
        $("#loader").html('<img src=\'/images/ajax.gif\'>Please Wait...').addClass('error');
    }

    $("#frmNewAoc").ajaxForm(function(data){
        if(data=="exists"){
            $("#errCotainer").html("An AOC Holder with this record exists.").addClass('error');
            return false;
        }
        else{
            if(data=="save"){
                $("#errormessage").html("AOC added successfully.").addClass('error');
                var url="";
                window.location.href=url;
            }
        }
    });

$("#unlockAocCertificateNo").click(function(){
    $ask = confirm("Are you sure, you want to upload another AOC Certificate No?");
    if($ask){
        $("#aoc_certificate").removeAttr("disabled");
        $("#aocCertificateNoCheck").val(1);
        $("#unlockAocCertificateNo").css({display:'none'});
    }
    return
});

$("#unlockOpsSpecs").click(function(){
    $ask = confirm("Are you sure, you want to upload another OPS Specs?");
    if($ask){
        $("#ops_specs").removeAttr("disabled");
        $("#opsSpecsCheck").val(1);
        $("#unlockOpsSpecs").css({display:'none'});
    }
    return
});

$("#unlockPartG").click(function(){
    $ask = confirm("Are you sure, you want to upload another PART G?");
    if($ask){
        $("#part_g").removeAttr("disabled");
        $("#partGCheck").val(1);
        $("#unlockPartG").css({display:'none'});
    }
    return
});



    // UPDATE THE AN AOC HOLDER RECORD

    $("#updateAocRecord").click((event)=>{
        event.preventDefault();
        validateAocUpdate();
    });

    const validateAocUpdate = () => {
        $('#errContainer').html('').removeClass('error');
        $aoc_holder = $("#aoc_holder").val();
            if(!$aoc_holder){
                $("#errContainer").html('- sorry, AOC Holder is required*').addClass('error');
                $("#aoc_holder").focus();
                return false;
            }
        $aoc_certificate_no = $("#aoc_certificate_no").val();
        if(!$aoc_certificate_no){
            $("#errContainer").html('- AOC Certificate Number is required*').addClass('error');
            $("#aoc_certificate_no").focus();
            return false;
        }
        $aoc_certificate = $("#aoc_certificate").val();
        $aocCertificateNoCheck = $("#aocCertificateNoCheck").val();
        if($aocCertificateNoCheck==1 && $aoc_certificate == '')
        {
            $("#errContainer").html('- AOC Certificate is required*').addClass('error');
            return
        }else{
            if($aoc_certificate !=""){
                var ftype = $("#ftype").val();
                validateaoccertfile(ftype);
                var filecheck = $("#filecheck").val();
                if(filecheck == "0"){return;}
            }
        }
            
        $issued_date = $("#issued_date").val();
            if(!$issued_date){
                $("#errContainer").html('- choose an issue date for the AOC holder').addClass('error');
                $("#issued_date").focus();
                return false;
            }
        $validity = $("#validity").val();
            if(!$validity){
                $("#errContainer").html('- choose the validity date for the AOC holder').addClass('error');
                $("#validity").focus();
                return false;
            }
        $ops_specs = $("#ops_specs").val();
        $opsSpecsCheck = $("#opsSpecsCheck").val();
        if($opsSpecsCheck == 1 && $ops_specs == '')
        {
            $("#errContainer").html('- OPS Specs is required *').addClass('error');
            return
        }
        else{
            if($ops_specs !=""){
                var ftype = $("#ftype").val();
                validateOpsSpecs(ftype);
                var filecheck = $("#filecheck").val();
                if(filecheck == "0"){return;}
            }
        }
              
        $part_g = $("#part_g").val();
        $partGCheck = $("#partGCheck").val();
        if($partGCheck == 1 && $part_g == '')
        {
            $("#errContainer").html('- PART G is required. *').addClass('error');
            return
        }
        else{
            if($part_g !=""){
                var ftype = $("#ftype").val();
                validatePartG(ftype);
                var filecheck = $("#filecheck").val();
                if(filecheck == "0"){return;}
            }
        }
        $remarks = $("#remarks").val();
            if(!remarks){
                $("#errContainer").html('- Choose the remark of the AOC Holder').addClass('error');
                return false;  
            }
        $("#loader").html('<img src=\'/images/ajax.gif\'>Please Wait...').addClass('error');
        $("#frmUpdateAoc").submit();    
    }

    $("#frmUpdateAoc").ajaxForm(function(data){
        if(data=="exists"){
            $("#errCotainer").html("An AOC Holder with this record exists.").addClass('error');
            return false;
        }
        else{
            if(data=="updated"){
                $("#errormessage").html("Aoc  updated successfully.").addClass('error');
                var url="/new-aoc";
                window.location.href=url;
            }
        }
    });

    //-> click function to add a/c type operated by an AOC...
    $("#addAocAircraft").click((event)=>{
        event.preventDefault();
        validateAocAircraftMake();
        $aoc_holder_id = $("#aoc_holder_id").val();
        $.post('/addaocaircraftmake', $('#frmaocaircraft').serialize(), function(data){
            if(data === 'exist'){
                $("#errCotainer").html("Aircraft has already been added.").addClass('error');
                return false;
                }
            else{
                if(data == 'saved'){
                    $("#loader").html('<img src=\'/images/ajax.gif\'>Please Wait...').addClass('error');
                    window.location = '';
                }
                else{
                    $("#errCotainer").html("Oops! Something went wrong. Internal Server Error.").addClass('error');
                    return false;
                }
            }
        })
    });

    const validateAocAircraftMake = () => {
        $aircraft_maker_id = $('#aircraft_maker_id').val();
            if($aircraft_maker_id === ''){
                $("#loader").html('choose an aircraft make.').addClass('error');
                return false;
            }
    }

    $("#downloadAOC").click(function(){
        var name = Math.random().toString().substring(7);
        $("#exportTableData").table2excel({
            filename:`aoc-${name}.xls`
        });
    });

    //Delete an AOC
    $(".deleteAoc").click(function(){
        $id = $(this).attr("value");
        $name = $(this).attr("title");
        $ask = confirm("Are you sure you want to "+$name.toLowerCase()+"?");
        if($ask){
            $("#deleteLoader")
            .html(
                '<img src=\'/images/ajax.gif\'>please wait...'
            )
            .addClass('error');
            $.post("/new-aoc/"+$id, $("#deleteAoc").serialize(), function(data){
               if(data=='cant_delete'){
                   $("#deleteLoader").html('<i class="mdi mdi-alert"></i>This AOC has been assigned to records of either AC/Status, FOCC  or Local AMO in one or more places.');
                   return;
               }
               $url = '/new-aoc';
               window.location = $url;
            })
        }
        return;
    });








    //change function to get all aocs by remarks.
    $("#remarks").change(function(){
        $remarks = $("#remarks").val();
        $("#contentDropper").html("<img src='/images/ajax.gif'>Please wait...").addClass('error');
        $.post('/aoc-by-remarks', $("#frmSortAoc").serialize(), function(data){
            $("#contentDropper").html(data).removeClass('error');
        });
    })

    //change function to get all aocs by issued date in ascending or descending order.
    $("#issuedDate").change(function(){
        $issuedDate = $("#issuedDate").val();
        $("#contentDropper").html("<img src='/images/ajax.gif'>Please wait...").addClass('error');
        $.post('/aoc-by-issued-date', $("#frmSortAoc").serialize(), function(data){
            $("#contentDropper").html(data).removeClass('error');
        });
    })

    //change function to get all aocs by operation.
    $("#operation").change(function(){
        $operation = $("#operation").val();
        $("#contentDropper").html("<img src='/images/ajax.gif'>Please wait...").addClass('error');
        $.post('/aoc-by-operation', $("#frmSortAoc").serialize(), function(data){
            $("#contentDropper").html(data).removeClass('error');
        });
    })











});



