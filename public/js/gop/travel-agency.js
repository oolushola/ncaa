$(function(){
    $("#addTravelAgency").click(function(e){
        e.preventDefault();
        $travel_agency_name = $("#travelAgencyName").val();
        if($travel_agency_name == ""){
            $("#loader").html('Name of travel agency is required.').addClass('error');
            return
        }
        $("#loader").html('<img src="/images/ajax.gif">Please wait...').addClass('error');
        $.post("/travel-agency", $("#frmTravelAgency").serialize(), function(data){ 
            if(data == 'exist'){
                $("#loader").html(`<i class=\"mdi mdi-alert\"></i> ${data} already exists.`);
                return;
            }
            $url= '/travel-agency';
            window.location = $url;
        });
    });

    $("#updateTravelAgency").click(function(e){
        e.preventDefault();
        $travel_agency_name = $("#travelAgencyName").val();
        if($travel_agency_name == ""){
            $("#loader").html('Name of travel agency is required.').addClass('error');
            return
        }
        $id = $("#id").val();
        $("#loader").html('<img src="/images/ajax.gif">Please wait...').addClass('error');
        $.post("/travel-agency/"+$id, $("#frmTravelAgency").serialize(), function(data){
            if(data == 'exists'){
                $("#loader").html(`<i class=\"mdi mdi-alert\"></i> ${data} already exists.`);
                return;
            }
            $url= '/travel-agency';
            window.location = $url;
        });
    });

    /**
     * click delete event
     * 
     * @request aircraft type id
     * 
     * @return string
     */
    $(".deleteTrainingOrganization").click(function(){
        $id = $(this).attr("value");
        $name = $(this).attr("title");
        $ask = confirm("Are you sure you want to delete "+$name.toLowerCase()+"?");
        if($ask){
            $("#deleteLoader")
            .html(
                '<img src=\'/images/ajax.gif\'>please wait...'
            )
            .addClass('error');
            $.post("/training-organization/"+$id, $("#frmDeleteTrainingOrganization").serialize(), function(data){
               if(data=='cant_delete'){
                   $("#deleteLoader").html("<i class='mdi mdi-alert'></i> Sorry, training organization is in use by a ATO Record. Won't Delete.").css({color:'green'});
                   return;
               }
               $url = '/training-organization';
               window.location = $url;
            })
        }
        return;
    });

})