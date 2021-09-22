$(function($){
  $("#addForeingAirlineDacl").click(function(event){
      event.preventDefault();
      if(validatDaclRequest()==false) return;
      $("#contentDropper").html('<img src=\'/images/ajax.gif\'>please wait...').addClass('error');
      $("#foreignAirlineDacl").submit();
  });

  $("#updateForeingAirlineDacl").click(function(event){  
      event.preventDefault();
      if(validatDaclRequest()==false) return;
      $("#contentDropper").html('<img src=\'/images/ajax.gif\'>please wait...').addClass('error');
      $("#foreignAirlineDacl").submit();
  });

  $("#changeDacl").click(function() {
      $(this).css({display:'none'});
      $("#file").removeAttr('disabled', 'disabled');
      $("#daclChecker").val(1);
  })

  $("#changeAocOpspec").click(function() {
    $(this).css({display:'none'});
    $("#file2").removeAttr('disabled', 'disabled');
    $("#opspecChecker").val(1);
})

  function validatDaclRequest() {
      $airline_name = $("#airlineName").val();
      if($airline_name === '0'){
        $("#loader").html("Foreign airline name is required").addClass('error');
        return false;
      }
      $approvalNumber = $("#approval_no").val();
      if($approvalNumber == ""){
          $("#loader").html("Approval Number  is required").addClass('error');
          return false;
      }

      $file = $("#file").val();
      $daclChecker = $("#daclChecker").val();
      if($file == "" && $daclChecker == 0) {
          $("#loader").html("foreign dacl certificate upload is required").addClass('error');
          return false;
      }
      else{
          if($file == "" && $daclChecker == 1){
              $("#loader").html("foreign dacl certificate upload is required").addClass('error');
              return false;
          }
          if($file !=""){
              var ftype = $("#ftype").val();
              validateFile(ftype);
              var filecheck = $("#filecheck").val();
              if(filecheck == "0"){return;}
          }
      }

      $daclIssueDate = $("#daclIssueDate").val();
      if($daclIssueDate == ""){
          $("#loader").html("Dacl issue date is required").addClass('error');
          return false;
      }

      $file2 = $("#file2").val();
      $opspecChecker = $("#opspecChecker").val();
      if($file == "" && $opspecChecker == 0) {
          $("#loader").html("Aoc operation spec certificate upload is required").addClass('error');
          return false;
      }
      else{
          if($file2 == "" && $opspecChecker == 1){
              $("#loader").html("Aoc operation spec certificate upload is required").addClass('error');
              return false;
          }
          if($file2 !=""){
              var ftype = $("#ftype").val();
              validateFile(ftype);
              var filecheck = $("#filecheck").val();
              if(filecheck == "0"){return;}
          }
      }

      $aocExpiryDate = $("#aocExpiryDate").val();
      if($aocExpiryDate == ""){
          $("#loader").html("Aoc expiry date is required").addClass('error');
          return false;
      }

      $country = $("#country").val();
      if($country == "0"){
          $("#loader").html("Country of foreign airline dacl is required").addClass('error');
          return false;
      }
  }

  $("#foreignAirlineDacl").ajaxForm(function(data) {
      if(data == 'exists') {
          $("#loader").html("This record already exists").addClass('error');
          return false;
      }
      else {
          if(data == 'saved' || data == "updated") {
              $("#loader").html("Record Succesfully "+data).addClass('error');
              window.location = '/foreign-airline-dacl';
          }
      }
  })

  $("#downloadDacl").click(function(){
    var name = Math.random().toString().substring(7);
    $("#exportTableData").table2excel({
        filename:`foreign-airline-dacl-${name}.xls`
    });
  });

  //Delete a DACL
  $(".deleteforeignAirlineDacl").click(function(){
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
          $.post("/foreign-airline-dacl/"+$id, $("#frmDeleteforeignAirlineDacl").serialize(), function(data){
             if(data=='deleted'){
                 $url = '/foreign-airline-dacl';
                 window.location = $url;
             }
          })
      }
      return;
  });
  
  // on change for filtering status
  $("#chooseStatus").on("change", function(){
      $status = $("#chooseStatus").val();
      if($status == '0'){
          return false;
      }
      
  });

  $("#sortCountry").change(function() {
    if($(this).val() == 0){
        return false;
    }
    $("#contentDropper").html('<img src=\'/images/ajax.gif\'>please wait...').addClass('error');
    $.get('/foreign-airline-dacl-country', { country: $(this).val() }, function(data) {
        $("#contentDropper").html(data).removeClass("error");
    });
  })

  $("#chooseStatus").change(function() {
    if($(this).val() == 0){
        return false;
    }
    $("#contentDropper").html('<img src=\'/images/ajax.gif\'>please wait...').addClass('error');
    $.get('/foreign-airline-dacl-status', { status: $(this).val() }, function(data) {
        $("#contentDropper").html(data).removeClass("error");
    });
  }) 


});