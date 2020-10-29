$(function(){
    
    $('#addForeignAmoHolder').click((event)=>{
        event.preventDefault();
        validateForeignAmoHolder();
    });

    $('#updateForeignAmoHolder').click((event)=>{
        event.preventDefault();
        validateUpdateForeignAmoHolder();
    });


    const validateForeignAmoHolder = () => {
        $foreign_amo_holder = $("#foreign_amo_holder").val();
            if(!$foreign_amo_holder){
                $("#errContainer").html(' - Foreign amo holder is required.').addClass('error');
                $("#foreign_amo_holder").focus();
                return false;
            }
        $.post('/foreign-amo-holder', $("#frmAddForeignAmoHolder").serialize(), function(data){
            if(data === 'exists'){
                $("#errContainer").addClass('error').html('Sorry, this foreign amo holder already exists.');
                return false;
            }
            else{
                if(data === 'save'){
                    $("#loader").html('<img src=\'/images/ajax.gif\'>Please Wait...').addClass('error');
                    $url = '';
                    window.location = $url;
                }
            }
        })
    }

    const validateUpdateForeignAmoHolder = () => {
        $foreign_amo_holder = $("#foreign_amo_holder").val();
            if(!$foreign_amo_holder){
                $("#errContainer").html(' -  Foreign amo holder is required.').addClass('error');
                $("#foreign_amo_holder").focus();
                return false;
            }
        $id = $("#id").val();
        $.post('/foreign-amo-holder/'+$id, $("#frmAddForeignAmoHolder").serialize(), function(data){
            if(data === 'exists'){
                $("#errContainer").addClass('error').html('Sorry, this foreign amo holder already exists.');
                return false;
            }
            else{
                if(data === 'updated'){
                    $("#loader").html('<img src=\'/images/ajax.gif\'>Please Wait...').addClass('error');
                    $url = '/foreign-amo-holder';
                    window.location = $url;
                }
            }
        })
    }

    // Delete Aircraft maker
    $(".deleteForeignAmoHolder").click(function(){
        $id = $(this).attr("value");
        $name = $(this).attr("title");
        $ask = confirm("Are you sure you want to "+$name.toLowerCase()+"?");
        if($ask){
            $("#deleteLoader")
            .html(
                '<img src=\'/images/ajax.gif\'>please wait...'
            )
            .addClass('error');
            $.post("/foreign-amo-holder/"+$id, $("#frmdeleteforeignamoholder").serialize(), function(data){
               if(data=='cant_delete'){
                   $("#deleteLoader").html("<i class='mdi mdi-alert'></i> Oops, this AMO holder is in use in foreign Amo").css({color:'green'});
                   return;
               }
               else{
                   if(data == "deleted") {
                        $url = '/foreign-amo-holder';
                        window.location = $url;
                   }
               }
               
            })
        }
        return;
    });
})