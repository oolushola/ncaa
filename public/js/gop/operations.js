$(function(){
    
    $('#addNewOperations').click((event)=>{
        event.preventDefault();
        validateOperations();
        sender('operations');
    });

    $('#updateOperations').click((event)=>{
        event.preventDefault();
        validateOperations();
        $id = $("#id").val();
        sender(`operations/${$id}`);
    });


    const validateOperations = () => {
        $operation_type = $("#operation_type").val();
            if(!$operation_type){
                $("#loader").html('Operation type is required.').addClass('error');
                $("#operation_type").focus();
                return false;
            }
    }


    function sender(url){
        $.post(`/${url}`, $("#frmOperations").serialize(), function(data){
            if(data === 'exists'){
                $("#loader").addClass('error').html('Sorry, this operation type has already been added');
                return false;
            }
            else{
                if(data === 'saved'){
                    $("#loader").html('<img src=\'/images/ajax.gif\'>Please Wait...').addClass('error');
                    $url = '';
                    window.location = $url;
                }
                if(data === 'updated'){
                    $("#loader").html('<img src=\'/images/ajax.gif\'>Please Wait...').addClass('error');
                    $url = '';
                    window.location = $url;
                }
            }
        })
    }

    // Delete Operation Type
    $(".deleteOperationType").click(function(){
        $id = $(this).attr("value");
        $name = $(this).attr("title");
        $ask = confirm("Are you sure you want to "+$name.toLowerCase()+"?");
        if($ask){
            $("#deleteLoader")
            .html(
                '<img src=\'/images/ajax.gif\'>please wait...'
            )
            .addClass('error');
            $.post("/operations/"+$id, $("#deleteOperationType").serialize(), function(data){
               if(data=='cant_delete'){
                   $("#deleteLoader").html("<i class='mdi mdi-alert'></i> Sorry, the Operation Type has been assigned to one or more AOC. Won't Delete.").css({color:'green'});
                   return;
               }
               $url = '/operations';
               window.location = $url;
            })
        }
        return;
    });

})