$(document).ready(function(){
   
       $(document).on("change", "#aoc_holder_id", function(){
        $('#contentLoader').html('<img src=\'/images/ajax.gif\'>Please wait...').addClass('error');
        $.post('/getlistings', $("#frmAssignOperationToAoc").serialize(), function(data){
            $('#contentLoader').html(data).removeClass('error');
        });
    });

    $(document).on('click', '#selectAllLeftchkbx', function(){
        $check = $(this).is(':checked');
        if($check){
            $("#selectAllLeftTxt").html('Deselect All Available Operation Specs listings');
            $(".operationTypeLeftChkbx").attr('checked', 'checked');
            $("#checkboxValidator").val(1);
        }
        else{
            $("#selectAllLeftTxt").html('Select All Available Operation Specs listings');
            $(".operationTypeLeftChkbx").removeAttr('checked');
            $("#checkboxValidator").val(0);
        }
    });

    $(document).on("click", ".operationTypeLeftChkbx", function(){
        $checked = $(this).is(":checked");
        if($checked){
            $("#checkboxValidator").val(1);
        }
        else{
            $("#checkboxValidator").val(0);
        }
    });

    $(document).on('click', '#assignoperationSpecs', function(){
        $("#loader").removeClass('error').html('');
        $aoc_holder_id = $("#aoc_holder_id").val();
            if($aoc_holder_id === ''){
                $("#aoc_holder_id").focus();
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>You have to choose an AOC Holder before assigning any operation.').addClass('error');
                return false;
            }
        $checkboxValidator = $("#checkboxValidator").val();
        if($checkboxValidator == 0){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>You have to click on one or more checkbox on the left before assigning operations to this AOC Holder').addClass('error');
                return false;   
            }
        $.post('/assign-operation-type-to-aoc', $("#frmAssignOperationToAoc").serialize(), function(data){
            $('#contentLoader').html('<img src=\'/images/ajax.gif\'>Please wait...').addClass('error');
            $('#contentLoader').html(data).removeClass('error');
        });
    });


    $(document).on('click', '#selectAllRightchkbx', function(){
        $check = $(this).is(':checked');
        if($check){
            $("#assignedRightLabel").html('Deselect all assigned operation specification');
            $(".operationTypeRightChkbx").attr('checked', 'checked');
            $("#checkboxValidator").val(1);
        }
        else{
            $("#assignedRightLabel").html('Select all assigned operation specification');
            $(".operationTypeRightChkbx").removeAttr('checked');
            $("#checkboxValidator").val(0);
        }
    });

    $(document).on("click", ".operationTypeRightChkbx", function(){
        $checked = $(this).is(":checked");
        if($checked){
            $("#checkboxValidator").val(1);
        }
        else{
            $("#checkboxValidator").val(0);
        }
    });

    $(document).on('click', '#removeOprSpecs', function(){
        $("#loader").removeClass('error').html('');
        $aoc_holder_id = $("#aoc_holder_id").val();
            if($aoc_holder_id === ''){
                $("#aoc_holder_id").focus();
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>You have to choose an AOC Holder before assigning any operation.').addClass('error');
                return false;
            }
        $checkboxValidator = $("#checkboxValidator").val();
        if($checkboxValidator == 0){
                $("#loader").html('<i class=\'mdi mdi-alert\'></i>You have to click on one or more checkbox on the right before removing operations from this AOC Holder').addClass('error');
                return false;   
            }
        $.post('/remove-operation-type-from-aoc', $("#frmAssignOperationToAoc").serialize(), function(data){
            $('#contentLoader').html('<img src=\'/images/ajax.gif\'>Please wait...').addClass('error');
            $('#contentLoader').html(data).removeClass('error');
        });
    }); 
});