$(function() {
    $('#aircraftMaker').change(function() {
        $aircraft_maker_id = $(this).val()
        $('#aircraftModel').html('<img src="/images/ajax.gif">Please wait...');
        $.get('/get-aircraft-models', {aircraft_maker_id:$aircraft_maker_id}, function(data) {
            $('#aircraftModel').html(data);
        })
    })

    $("#addTac").click(function() {
        $aircraftMaker = $('#aircraftMaker').val();
        if($aircraftMaker == "0") {
            $('#loader').html('Aircraft manufacturer is required.').addClass('error');
            return false;
        }
        $tc_acceptance_approval = $('#tc_acceptance_approval').val();
        if($tc_acceptance_approval == "") {
            $('#loader').html('TC Acceptance Approval Certificate No. is required.').addClass('error');
            return false;
        }
        
        $date_issued = $('#date_issued').val();
        if($date_issued == "") {
            $('#loader').html('Date issued is required.').addClass('error');
            return false;
        }

        $original_tc_issued_by = $('#original_tc_issued_by').val();
        if($original_tc_issued_by == "") {
            $('#loader').html('Original TC Issued by is required.').addClass('error');
            return false;
        }

        $tc_holder = $('#tc_holder').val();
        if($tc_holder == "0") {
            $('#loader').html('TC Holder is required.').addClass('error');
            return false;
        }
        
        $tc_no = $('#tc_no').val();
        if($tc_no == "") {
            $('#loader').html('TC No. is required.').addClass('error');
            return false;
        }

    
        $('#loader').html('<img src="/images/ajax.gif">Please wait...');
        $('#frmTac').submit();


    });

    $('.deleteTac').click(function() {
        $id = $(this).attr("value");
        $name = $(this).attr("name");
        $ask = confirm('Are you sure you want to delete '+$name);
        if($ask){
            $.post("/type-acceptance-certificate/"+$id, $('#deleteTac').serialize(), function(data){
                if(data == "deleted"){
                    alert($name+' has successfully been deleted')
                    window.location = "/type-acceptance-certificate";
                }
            })
        }
        else{
            return false;
        }
    });

    $("#downloadTac").click(function(){
        var name = Math.random().toString().substring(7);
        $("#exportTableData").table2excel({
            filename:`Type-Acceptance-Certificate-${name}.xls`
        });
    });
    
    $("#sortTac").change(function() {
        $("#contentDropper").html('<img src=\'/images/ajax.gif\'>please wait...').addClass('error');
        $.get('/sort-type-acceptance', { sort: $(this).val()}, function(data) {
            $("#contentDropper").html(data).removeClass("error")
        })
    })

    $("#changeStatus").change(function() {
        $("#contentDropper").html('<img src=\'/images/ajax.gif\'>please wait...').addClass('error');
        $.get('/filter-tac-status', { sort: $("#sortTac").val(), status: $(this).val()}, function(data) {
            $("#contentDropper").html(data).removeClass("error")
        })
    })

    $('#frmTac').ajaxForm(function(data) {
        if(data == "exists") {
            $('#loader').html("Record already exists for this Aircraft Manufacturer");
            return false
        }
        else {
            $('#loader').html("Record saved successfully");
            window.location="";
        }
    });

   

    

})