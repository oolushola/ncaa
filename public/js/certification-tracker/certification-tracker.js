$(function($){
  $("#addCertificationTracker").click(function(event){
    event.preventDefault();
    if(validateCertificationTracker()==false) return;
    $.post('/certification-tracker', $('#frmCertificationTracker').serializeArray(), function(data) {
      if(data === 'saved') {
        const url = '/certification-tracker'
        window.location = url
      }
      else{
        alert('Oops! something went wrong')
        return false
      }
    })
  });

  $("#updateCertificationTracker").click(function(event){  
    event.preventDefault();
    if(validateCertificationTracker()==false) return;
    const id = $("#id").val()
    $.post('/certification-tracker/'+id, $('#frmCertificationTracker').serializeArray(), function(data) {
      if(data === 'updated') {
        const url = '/certification-tracker'
        window.location = url
      }
      else{
        alert('Oops! something went wrong')
        return false
      }
    })
  });

  function validateCertificationTracker() {
      $certificationNo = $("#certificationNo").val();
      if($certificationNo === ''){
        $("#loader").html("Certification tracker no is required").addClass('error');
        return false;
      }
      $dateAssigned = $("#dateAssigned").val();
      if($dateAssigned == ""){
          $("#loader").html("Date assigned  is required").addClass('error');
          return false;
      }
      $applicantName = $("#applicantName").val();
      if($applicantName == ""){
          $("#loader").html("Applicant name is required").addClass('error');
          return false;
      }
      $certificationType = $("#certificationType").val();
      if($certificationType == ""){
          $("#loader").html("Certification type is required").addClass('error');
          return false;
      }
      $cpm = $("#cpm").val();
      if($cpm == ""){
          $("#loader").html("CPM is required").addClass('error');
          return false;
      }
      $teamMember = $("#teamMember").val();
      if($teamMember == ""){
          $("#loader").html("Team member is required").addClass('error');
          return false;
      }

      $startDate = $("#startDate").val();
      if($startDate == ""){
          $("#loader").html("Start date is required").addClass('error');
          return false;
      }

      $completionDate = $("#completionDate").val();
      if($completionDate == ""){
          $("#loader").html("Completion date is required").addClass('error');
          return false;
      }

      $status = $("#status").val();
      if($status == ""){
          $("#loader").html("Status is required").addClass('error');
          return false;
      }
      $aircraftType = $("#aircraftType").val();
      if($aircraftType == ""){
          $("#loader").html("Aircraft type is required").addClass('error');
          return false;
      }
  }

  $("#downloadCertificationTracker").click(function(){
    var name = Math.random().toString().substring(7);
    $("#exportTableData").table2excel({
        filename:`certification-tracker-${name}.xls`
    });
  });

  $(".deleteCertificationTracker").click(function(){
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
          $.post("/certification-tracker/"+$id, $("#frmDeleteCertificationTracker").serialize(), function(data){
             if(data=='deleted'){
                 $url = '/certification-tracker';
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
});